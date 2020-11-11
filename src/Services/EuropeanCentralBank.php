<?php

declare(strict_types=1);

/*
 * This file is part of Topia.Money.
 *
 * (c) Konceiver <info@konceiver.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Konceiver\TopiaMoney\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Konceiver\TopiaMoney\Contracts\Service;
use Konceiver\TopiaMoney\DTO\Rate;
use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Helpers\Client;

/**
 * Undocumented class.
 */
final class EuropeanCentralBank implements Service
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * Undocumented function.
     */
    public function __construct()
    {
        $this->client = Client::new('https://www.ecb.europa.eu/stats/eurofxref');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        $symbols = $this
            ->getDays()
            ->unique('symbol')
            ->pluck(['target'])
            ->flatten()
            ->toArray() + ['EUR'];

        $result = [];

        foreach ($symbols as $source) {
            foreach ($symbols as $target) {
                if ($source === $target) {
                    continue;
                }

                $result[] = new Symbol([
                    'symbol'   => $source.'-'.$target,
                    'source'   => $source,
                    'sourceId' => $source,
                    'target'   => $target,
                    'targetId' => $target,
                ]);
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Symbol $symbol): array
    {
        // @TODO: support XXX > EUR. Currently only supports EUR > XXX
        return $this
            ->getDays()
            ->filter(fn ($rate) => $rate['symbol'] === $symbol->symbol)
            ->map(fn ($rate)    => new Rate([
                'date'  => Carbon::parse($rate['date']),
                'rate'  => $rate['rate'],
            ]))
            ->values()
            ->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function rate(Symbol $symbol): Rate
    {
        // @TODO: support XXX > EUR. Currently only supports EUR > XXX
        $rate = $this->getDays()->reverse()->last();

        return new Rate([
            'date' => Carbon::parse($rate['date']),
            'rate' => $rate['rate'],
        ]);
    }

    private function getDays(): Collection
    {
        $response = $this->client->get('eurofxref-hist.xml')->body();
        $days     = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA)->Cube;

        $result = [];
        foreach ($days->children() as $child) {
            $child = json_decode(json_encode((array) $child), true);

            foreach ($child['Cube'] as $rate) {
                $attributes = $rate['@attributes'];

                $result[] = [
                    'symbol' => 'EUR-'.$attributes['currency'],
                    'source' => 'EUR',
                    'target' => $attributes['currency'],
                    'date'   => $child['@attributes']['time'],
                    'rate'   => $attributes['rate'],
                ];
            }
        }

        return new Collection($result);
    }
}

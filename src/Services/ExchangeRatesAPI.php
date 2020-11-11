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
use Konceiver\TopiaMoney\Contracts\Service;
use Konceiver\TopiaMoney\DTO\Rate;
use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Helpers\Client;

/**
 * Undocumented class.
 */
final class ExchangeRatesAPI implements Service
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
        $this->client = Client::new('https://api.exchangeratesapi.io/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        $symbols = array_keys($this->client->get('latest')['rates']) + ['EUR'];

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
        $response = $this->client->get('history', [
            'base'     => $symbol->source,
            'start_at' => Carbon::now()->startOfCentury()->format('Y-m-d'),
            'end_at'   => Carbon::now()->format('Y-m-d'),
        ])->json()['rates'];

        return collect($response)->transform(fn ($rates, $date) => new Rate([
            'date'  => Carbon::parse($date),
            'rate'  => (string) $rates[$symbol->target],
        ]))->values()->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function rate(Symbol $symbol): Rate
    {
        $rate = $this->client->get('latest', ['base' => $symbol->source])->json();

        return new Rate([
            'date' => Carbon::parse($rate['date']),
            'rate' => (string) $rate['rates'][$symbol->target],
        ]);
    }
}

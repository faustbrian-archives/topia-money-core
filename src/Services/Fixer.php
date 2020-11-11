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
use Illuminate\Http\Client\Response;
use Konceiver\TopiaMoney\Contracts\Service;
use Konceiver\TopiaMoney\DTO\Rate;
use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Helpers\Client;

/**
 * Undocumented class.
 */
final class Fixer implements Service
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @var string
     */
    protected ?string $authToken;

    /**
     * Undocumented function.
     */
    public function __construct()
    {
        $this->client = Client::new('https://data.fixer.io/api/');
    }

    /**
     * Undocumented function.
     *
     * @param string $token
     *
     * @return void
     */
    public function setAuthToken(string $token): self
    {
        $this->authToken = $token;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        $symbols = array_keys($this->get('symbols')['symbols']);

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
        $response = $this->get('timeseries', [
            'base'       => $symbol->source,
            'start_date' => Carbon::now()->startOfCentury()->format('Y-m-d'),
            'end_date'   => Carbon::now()->format('Y-m-d'),
        ])['rates'];

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
        $rate = $this->get('latest', ['base' => $symbol->source]);

        return new Rate([
            'date' => Carbon::parse($rate['date']),
            'rate' => (string) $rate['rates'][$symbol->target],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    private function get(string $path, array $query = []): Response
    {
        return $this->client->get($path, array_merge(
            ['access_key' => $this->authToken],
            $query
        ));
    }
}

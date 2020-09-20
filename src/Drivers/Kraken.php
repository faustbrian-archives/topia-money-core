<?php

declare(strict_types=1);

/*
 * This file is part of Common Crypto Exchange.
 *
 * (c) KodeKeep <hello@kodekeep.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KodeKeep\CommonCryptoExchange\Drivers;

use Carbon\Carbon;
use KodeKeep\CommonCryptoExchange\Contracts\Exchange;
use KodeKeep\CommonCryptoExchange\Helper\Client;
use KodeKeep\CommonCryptoExchange\Helpers\ResolveScientificNotation;

final class Kraken implements Exchange
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
        $this->client = new Client('https://api.kraken.com/0/public');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        $response = $this->client->get('AssetPairs')->json();

        return collect($response['result'])->transform(fn ($symbol) => [
            'symbol' => $symbol['altname'],
            'source' => $symbol['base'],
            'target' => $symbol['quote'],
        ])->values()->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function historical(string $source, ?string $target): array
    {
        $response = head($this->client->get('OHLC', [
            'pair'     => $source,
            'interval' => 1440,
            'since'    => 0,
        ])->json()['result']);

        return array_map(fn ($day) => [
            'date' => (string) Carbon::createFromTimestamp($day[0]),
            'rate' => $day[4],
        ], $response);
    }

    /**
     * {@inheritdoc}
     */
    public function price(string $source, ?string $target): string
    {
        $response = head($this->client->get('Ticker', ['pair' => $source])->json()['result']);

        return ResolveScientificNotation::execute($response['c'][0]);
    }
}

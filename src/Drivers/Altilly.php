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
use KodeKeep\CommonCryptoExchange\Enums\Ticker;
use KodeKeep\CommonCryptoExchange\Helper\Client;

final class Altilly implements Exchange
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
        $this->client = new Client('https://api.altilly.com/api/public');
    }

    /**
     * {@inheritdoc}
     */
    public function tickers(): array
    {
        $response = $this->client->get('symbol');

        return collect($response)->transform(fn ($ticker) => [
            'symbol' => $ticker['id'],
            'source' => $ticker['baseCurrency'],
            'target' => $ticker['quoteCurrency'],
        ])->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Ticker $ticker): array
    {
        $response = $this->client->get("candles/{$ticker->source}", [
            'period' => '24HR',
            'limit'  => 0,
        ])->json();

        return array_map(fn ($day) => [
            'date' => (string) Carbon::parse($day['timestamp']),
            'rate' => $day['close'],
        ], $response);
    }

    /**
     * {@inheritdoc}
     */
    public function price(Ticker $ticker): string
    {
        return '0';
    }
}

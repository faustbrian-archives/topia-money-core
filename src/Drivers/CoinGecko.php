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
use KodeKeep\CommonCryptoExchange\DTO\Rate;
use KodeKeep\CommonCryptoExchange\DTO\Ticker;
use KodeKeep\CommonCryptoExchange\Helper\Client;
use KodeKeep\CommonCryptoExchange\Helpers\ResolveScientificNotation;

/**
 * Undocumented class.
 */
final class CoinGecko implements Exchange
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
        $this->client = new Client('https://api.coingecko.com/api/v3');
    }

    /**
     * {@inheritdoc}
     */
    public function tickers(): array
    {
        return $this->client->get('coins/list')->json();
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Ticker $ticker): array
    {
        $response = $this->client->get('coins/'.strtolower($ticker->source).'/market_chart', [
            'vs_currency' => strtolower($ticker->target),
            'days'        => Carbon::now()->startOfCentury()->diffInDays(),
        ])->json();

        return array_map(fn ($day) => [
            'date'  => (string) Carbon::createFromTimestampMs($day[0]),
            'price' => ResolveScientificNotation::execute($day[1]),
        ], $response['prices']);
    }

    /**
     * {@inheritdoc}
     */
    public function price(Ticker $ticker): Rate
    {
        $source = strtolower($ticker->source);
        $target = strtolower($ticker->target);

        $response = $this->client->get('simple/price', [
            'ids'           => $source,
            'vs_currencies' => $target,
        ])->json()[$source][$target];

        return new Rate([
            'date' => '',
            'rate' => ResolveScientificNotation::execute($response),
        ]);
    }
}

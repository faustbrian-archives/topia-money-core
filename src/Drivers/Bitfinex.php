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

use KodeKeep\CommonCryptoExchange\Contracts\Exchange;
use KodeKeep\CommonCryptoExchange\DTO\Rate;
use KodeKeep\CommonCryptoExchange\DTO\Ticker;
use KodeKeep\CommonCryptoExchange\Helper\Client;
use KodeKeep\CommonCryptoExchange\Helpers\ResolveScientificNotation;

/**
 * Undocumented class.
 */
final class Bitfinex implements Exchange
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
        $this->client = new Client('https://api.bitfinex.com/');
    }

    /**
     * {@inheritdoc}
     */
    public function tickers(): array
    {
        $response = $this->client->get('v2/conf/pub:list:pair:exchange')->json();

        return array_map(fn ($ticker) => [
            'symbol' => $ticker,
            'source' => $ticker, // TODO
            'target' => $ticker, // TODO
        ], $response[0]);
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Ticker $ticker): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function price(Ticker $ticker): Rate
    {
        $response = $this->client->get("v1/pubticker/{$ticker->source}")->json();

        return new Rate([
            'date' => '',
            'rate' => ResolveScientificNotation::execute($response['last_price']),
        ]);
    }
}

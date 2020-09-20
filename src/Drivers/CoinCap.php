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
final class CoinCap implements Exchange
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
        $this->client = new Client('https://api.coincap.io/v2/');
    }

    /**
     * {@inheritdoc}
     */
    public function tickers(): array
    {
        // TODO: pagination
        $response = $this->client->get('markets')->json();

        return array_map(fn ($ticker) => [
            'symbol' => $ticker['exchangeId'], // TODO
            'source' => $ticker['baseId'],
            'target' => $ticker['quoteId'],
        ], $response['data']);
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
        $response = $this->client->get('rates/'.$ticker->source)->json();

        return new Rate([
            'date' => '',
            'rate' => ResolveScientificNotation::execute($response['data']['rateUsd']),
        ]);
    }
}

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
use KodeKeep\CommonCryptoExchange\Enums\Ticker;
use KodeKeep\CommonCryptoExchange\Helper\Client;
use KodeKeep\CommonCryptoExchange\Helpers\ResolveScientificNotation;

final class GDAX implements Exchange
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
        $this->client = new Client('https://api.gdax.com/');
    }

    /**
     * {@inheritdoc}
     */
    public function tickers(): array
    {
        return [];
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
    public function price(Ticker $ticker): string
    {
        $response = $this->client->get("products/{$ticker->source}-{$ticker->target}/ticker")->json();

        return ResolveScientificNotation::execute($response['price']);
    }
}

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
use KodeKeep\CommonCryptoExchange\DTO\Ticker;
use KodeKeep\CommonCryptoExchange\Helper\Client;
use KodeKeep\CommonCryptoExchange\Helpers\ResolveScientificNotation;

final class OKCoin implements Exchange
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
        $this->client = new Client('https://www.okcoin.com/api/');
    }

    /**
     * {@inheritdoc}
     */
    public function tickers(): array
    {
        $response = $this->client->get('spot/v3/instruments')->json();

        return array_map(fn ($ticker) => [
            'symbol' => $ticker['instrument_id'],
            'source' => $ticker['base_currency'],
            'target' => $ticker['quote_currency'],
        ], $response);
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
        $response = $this->client->get("spot/v3/instruments/{$ticker->source}/ticker")->json();

        return ResolveScientificNotation::execute($response['last']);
    }
}

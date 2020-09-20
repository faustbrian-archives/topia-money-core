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
use KodeKeep\CommonCryptoExchange\Helpers\ResolveScientificNotation;

final class CryptoCompare implements Exchange
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
        $this->client = new Client('https://min-api.cryptocompare.com');

        $this->client->withHeaders([
            'Authorization' => 'Apikey '.config('services.cryptocompare.token'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function tickers(): array
    {
        $response = $this->client->get('data/all/coinlist', ['limit' => 5000])->json();

        return collect($response['Data'])->map(fn ($ticker) => [
            'symbol' => $ticker['Symbol'],
            'source' => $ticker['Symbol'],
            'target' => 'USD',
        ])->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Ticker $ticker): array
    {
        $response = $this->client->get('data/histoday', [
            'fsym'    => $ticker->source,
            'tsym'    => $ticker->target,
            'allData' => true,
        ])->json();

        return array_map(fn ($data) => [
            'date'  => Carbon::createFromTimestamp($data['time']),
            'rate'  => ResolveScientificNotation::execute($data['close']),
        ], $response['Data']);
    }

    /**
     * {@inheritdoc}
     */
    public function price(Ticker $ticker): string
    {
        $response = $this->client->get('data/price', [
            'fsym'  => $ticker->source,
            'tsyms' => $ticker->target,
        ])->json()[$ticker->target];

        return ResolveScientificNotation::execute($response);
    }
}

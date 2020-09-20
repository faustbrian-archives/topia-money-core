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
use Illuminate\Support\Facades\Http;
use KodeKeep\CommonCryptoExchange\Contracts\Exchange;
use KodeKeep\CommonCryptoExchange\Helper\Client;
use KodeKeep\CommonCryptoExchange\Helpers\ResolveScientificNotation;

final class Bittrex implements Exchange
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
        $this->client = new Client('https://bittrex.com/api/v1.1/public');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        $response = $this->client->get('getmarkets')->json();

        return collect($response['result'])->transform(fn ($symbol) => [
            'symbol' => $symbol['MarketName'],
            'source' => $symbol['BaseCurrency'],
            'target' => $symbol['MarketCurrency'],
        ])->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function historical(string $source, ?string $target): array
    {
        $response = Http::get('https://global.bittrex.com/Api/v2.0/pub/market/GetTicks', [
            'marketName'   => $source,
            'tickInterval' => 'day',
        ])->json();

        return array_map(fn ($day) => [
            'date' => (string) Carbon::parse($day['T']),
            'rate' => ResolveScientificNotation::execute($day['C']),
        ], $response['result'] ?? []);
    }

    /**
     * {@inheritdoc}
     */
    public function price(string $source, ?string $target): string
    {
        $response = $this->client->get('getticker', ['market' => "{$source}"])->json()['result'];

        return ResolveScientificNotation::execute($response['Last']);
    }
}

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
use KodeKeep\CommonCryptoExchange\Exceptions\RateLimitException;
use KodeKeep\CommonCryptoExchange\Helper\Client;
use KodeKeep\CommonCryptoExchange\Helpers\ResolveScientificNotation;

final class Binance implements Exchange
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
        $response = $this->sendRequest('exchangeInfo');

        return collect($response['tickers'])->transform(fn ($ticker) => [
            'symbol' => $ticker['symbol'],
            'source' => $ticker['baseAsset'],
            'target' => $ticker['quoteAsset'],
        ])->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Ticker $ticker): array
    {
        // $start = Carbon::now()->subDays($startDays)->startOfDay();
        // $end   = $start->copy()->addDays($endDays)->startOfDay();

        // if ($start->diffInDays($end) > 1000) {
        //     throw new \Exception('Binance supports a maximum');
        // }

        $response = $this->sendRequest('klines', [
            'symbol'    => $ticker->source,
            'interval'  => '1d',
            // 'startTime' => $start->getPreciseTimestamp(3),
            // 'endTime'   => $end->getPreciseTimestamp(3),
            'limit'     => 1000,
        ]);

        return array_map(fn ($day) => [
            'date' => (string) Carbon::createFromTimestampMs($day[0]),
            'rate' => $day[4],
        ], $response);
    }

    /**
     * {@inheritdoc}
     */
    public function price(Ticker $ticker): string
    {
        $response = $this->sendRequest('ticker/price', compact('symbol'));

        return ResolveScientificNotation::execute($response['price']);
    }

    /**
     * Undocumented function.
     *
     * @param string $path
     * @param array  $query
     *
     * @return array
     */
    private function sendRequest(string $path, array $query = []): array
    {
        $response = $this->client->get($path, $query);

        $consumedWeight = (int) $response->header('x-mbx-used-weight-1m');

        if ($consumedWeight >= 1200) {
            throw new RateLimitException(60);
        }

        return $response->json();
    }

    /**
     * {@inheritdoc}
     */
    protected function baseUrl(): string
    {
        return 'https://api.binance.com/api/v3';
    }
}

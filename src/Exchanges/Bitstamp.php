<?php

declare(strict_types=1);

/*
 * This file is part of Topia.Money.
 *
 * (c) KodeKeep <hello@kodekeep.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KodeKeep\TopiaMoney\Exchanges;

use Carbon\Carbon;
use KodeKeep\TopiaMoney\Contracts\Exchange;
use KodeKeep\TopiaMoney\DTO\Rate;
use KodeKeep\TopiaMoney\DTO\Symbol;
use KodeKeep\TopiaMoney\Helpers\Client;

/**
 * Undocumented class.
 */
final class Bitstamp implements Exchange
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
        $this->client = Client::new('https://www.bitstamp.net/api/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Symbol $symbol): array
    {
        $days    = 1000;
        $results = [];

        do {
            $response = $this->client->get("v2/ohlc/{$symbol->symbol}", [
                'start'  => Carbon::now()->subDays($days)->unix(),
                'step'   => 86400,
                'limit'  => 1000,
            ]);

            $results = array_merge($results, $response['data']['ohlc']);

            $days += 1000;
        } while (! empty($response['data']['ohlc']));

        return array_map(fn ($day) => new Rate([
            'date' => Carbon::createFromTimestamp($day['timestamp']),
            'rate' => $day['close'],
        ]), $results);
    }

    /**
     * {@inheritdoc}
     */
    public function rate(Symbol $symbol): Rate
    {
        return new Rate([
            'date' => Carbon::now(),
            'rate' => '',
        ]);
    }
}

<?php

declare(strict_types=1);

/*
 * This file is part of Topia.Money.
 *
 * (c) Konceiver <info@konceiver.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Konceiver\TopiaMoney\Services;

use Carbon\Carbon;
use Konceiver\TopiaMoney\Contracts\Service;
use Konceiver\TopiaMoney\DTO\Rate;
use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Helpers\Client;

/**
 * Undocumented class.
 */
final class Bitstamp implements Service
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
        return array_map(function ($symbol) {
            [$source, $target] = str_split($symbol, 3);

            return new Symbol([
                'symbol'   => $symbol,
                'source'   => strtoupper($source),
                'sourceId' => strtoupper($source),
                'target'   => strtoupper($target),
                'targetId' => strtoupper($target),
            ]);
        }, [
            'btcusd',
            'btceur',
            'btcgbp',
            'btcpax',
            'gbpusd',
            'gbpeur',
            'eurusd',
            'xrpusd',
            'xrpeur',
            'xrpbtc',
            'xrpgbp',
            'xrppax',
            'ltcusd',
            'ltceur',
            'ltcbtc',
            'ltcgbp',
            'ethusd',
            'etheur',
            'ethbtc',
            'ethgbp',
            'ethpax',
            'bchusd',
            'bcheur',
            'bchbtc',
            'bchgbp',
            'paxusd',
            'paxeur',
            'paxgbp',
            'xlmbtc',
            'xlmusd',
            'xlmeur',
            'xlmgbp',
        ]);
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
            ])->json();

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
        $response = $this->client->get("v2/ohlc/{$symbol->symbol}", [
            'start'  => Carbon::now()->unix(),
            'step'   => 86400,
            'limit'  => 1,
        ])->json()['data']['ohlc'][0];

        return new Rate([
            'date' => Carbon::createFromTimestamp($response['timestamp']),
            'rate' => $response['close'],
        ]);
    }
}

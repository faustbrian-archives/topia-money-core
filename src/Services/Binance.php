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
use Konceiver\TopiaMoney\Helpers\ResolveScientificNotation;

/**
 * Undocumented class.
 */
final class Binance implements Service
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
        $this->client = Client::new('https://api.binance.com/api/v3/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        return array_map(fn ($symbol) => new Symbol([
            'symbol'   => $symbol['symbol'],
            'source'   => $symbol['baseAsset'],
            'sourceId' => $symbol['baseAsset'],
            'target'   => $symbol['quoteAsset'],
            'targetId' => $symbol['quoteAsset'],
        ]), $this->client->get('exchangeInfo')->json()['symbols']);
    }

    /**
     * @TODO: aggregate all records
     *
     * {@inheritdoc}
     */
    public function historical(Symbol $symbol): array
    {
        // $start = Carbon::now()->subDays($startDays)->startOfDay();
        // $end   = $start->copy()->addDays($endDays)->startOfDay();

        // if ($start->diffInDays($end) > 1000) {
        //     throw new \Exception('Binance supports a maximum');
        // }

        $response = $this->client->get('klines', [
            'symbol'    => $symbol->symbol,
            'interval'  => '1d',
            // 'startTime' => $start->getPreciseTimestamp(3),
            // 'endTime'   => $end->getPreciseTimestamp(3),
            'limit'     => 1000,
        ])->json();

        return array_map(fn ($day) => new Rate([
            'date' => Carbon::createFromTimestampMs($day[0]),
            'rate' => ResolveScientificNotation::execute((float) $day[4]),
        ]), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function rate(Symbol $symbol): Rate
    {
        $response = $this->client->get('ticker/price', [
            'symbol' => $symbol->symbol,
        ])->json();

        return new Rate([
            'date' => Carbon::now(),
            'rate' => ResolveScientificNotation::execute((float) $response['price']),
        ]);
    }
}

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
final class BitMEX implements Service
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
        $this->client = Client::new('https://www.bitmex.com/api/v1/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        return array_map(fn ($symbol) => new Symbol([
            'symbol'   => $symbol['symbol'],
            'source'   => $symbol['underlying'],
            'sourceId' => $symbol['underlying'],
            'target'   => $symbol['quoteCurrency'],
            'targetId' => $symbol['quoteCurrency'],
        ]), $this->client->get('instrument')->json());
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Symbol $symbol): array
    {
        return array_map(fn ($day) => new Rate([
            'date' => Carbon::parse($day['timestamp']),
            'rate' => ResolveScientificNotation::execute((float) $day['close']),
        ]), $this->client->get('trade/bucketed', [
            'binSize'   => '1d',
            'count'     => 1000,
            'reverse'   => true,
            'symbol'    => $symbol->symbol,
            'startTime' => Carbon::now()->startOfCentury()->toIso8601ZuluString(),
            'endTime'   => Carbon::now()->endOfDay()->toIso8601ZuluString(),
        ])->json());
    }

    /**
     * {@inheritdoc}
     */
    public function rate(Symbol $symbol): Rate
    {
        $response = $this->client->get('instrument', ['symbol' => $symbol->symbol])->json()[0];

        return new Rate([
            'date' => Carbon::now(),
            'rate' => ResolveScientificNotation::execute($response['lastPrice']),
        ]);
    }
}

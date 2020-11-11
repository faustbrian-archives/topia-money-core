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
final class HitBTC implements Service
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
        $this->client = Client::new('https://api.hitbtc.com/api/2/public/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        return array_map(fn ($symbol) => new Symbol([
            'symbol'   => $symbol['id'],
            'source'   => $symbol['baseCurrency'],
            'sourceId' => $symbol['baseCurrency'],
            'target'   => $symbol['quoteCurrency'],
            'targetId' => $symbol['quoteCurrency'],
        ]), $this->client->get('symbol')->json());
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Symbol $symbol): array
    {
        return array_map(fn ($day) => new Rate([
            'date' => Carbon::parse($day['timestamp']),
            'rate' => $day['close'],
        ]), head($this->client->get('candles', [
            'period'  => 'D1',
            'symbols' => $symbol->symbol,
            'limit'   => 1000,
        ])->json()));
    }

    /**
     * {@inheritdoc}
     */
    public function rate(Symbol $symbol): Rate
    {
        $response = head($this->client->get('ticker', ['symbols' => $symbol->symbol])->json());

        return new Rate([
            'date' => Carbon::parse($response['timestamp']),
            'rate' => ResolveScientificNotation::execute((float) $response['last']),
        ]);
    }
}

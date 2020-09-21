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
use KodeKeep\TopiaMoney\Helpers\ResolveScientificNotation;

/**
 * Undocumented class.
 */
final class HitBTC implements Exchange
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
            'symbol' => $symbol['id'],
            'source' => $symbol['baseCurrency'],
            'target' => $symbol['quoteCurrency'],
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

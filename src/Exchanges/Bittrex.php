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
        $this->client = Client::new('https://bittrex.com/api/v1.1/public/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        $response = $this->client->get('getmarkets')->json();

        return array_map(fn ($symbol) => new Symbol([
            'symbol' => $symbol['MarketName'],
            'source' => $symbol['BaseCurrency'],
            'target' => $symbol['MarketCurrency'],
        ]), $response['result']);
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Symbol $symbol): array
    {
        return array_map(fn ($day) => new Rate([
            'date' => Carbon::parse($day['T']),
            'rate' => ResolveScientificNotation::execute((float) $day['C']),
        ]), Client::new('https://global.bittrex.com/')->get('Api/v2.0/pub/market/GetTicks', [
            'marketName'   => $symbol->symbol,
            'tickInterval' => 'day',
        ])->json()['result'] ?? []);
    }

    /**
     * {@inheritdoc}
     */
    public function rate(Symbol $symbol): Rate
    {
        $response = $this->client->get('getticker', [
            'market' => "{$symbol->symbol}"
        ])->json()['result'];

        return new Rate([
            'date' => Carbon::now(),
            'rate' => ResolveScientificNotation::execute((float) $response['Last']),
        ]);
    }
}

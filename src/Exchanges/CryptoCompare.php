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
        $this->client = Client::new('https://min-api.cryptocompare.com/');
    }

    /**
     * Undocumented function.
     *
     * @param string $token
     *
     * @return void
     */
    public function setAuthToken(string $token): void
    {
        $this->client->withHeaders(['Authorization' => 'Apikey '.$token]);
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        return array_map(fn ($symbol) => new Symbol([
            'symbol' => $symbol['Symbol'],
            'source' => $symbol['Symbol'],
            'target' => 'USD',
        ]), $this->client->get('data/all/coinlist', [
            'limit' => 5000
        ])->json()['Data']);
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Symbol $symbol): array
    {
        return array_map(fn ($data) => new Rate([
            'date'  => Carbon::createFromTimestamp($data['time']),
            'rate'  => ResolveScientificNotation::execute($data['close']),
        ]), $this->client->get('data/histoday', [
            'fsym'    => $symbol->source,
            'tsym'    => $symbol->target,
            'allData' => true,
        ])->json()['Data']);
    }

    /**
     * {@inheritdoc}
     */
    public function rate(Symbol $symbol): Rate
    {
        $response = $this->client->get('data/price', [
            'fsym'  => $symbol->source,
            'tsyms' => $symbol->target,
        ])->json()[$symbol->target];

        return new Rate([
            'date' => Carbon::now(),
            'rate' => ResolveScientificNotation::execute($response),
        ]);
    }
}

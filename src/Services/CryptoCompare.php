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
final class CryptoCompare implements Service
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
            'symbol'   => $symbol['Symbol'],
            'source'   => $symbol['Symbol'],
            'sourceId' => $symbol['Symbol'],
            'target'   => 'USD',
            'targetId' => 'USD',
        ]), $this->client->get('data/all/coinlist', [
            'limit' => 5000,
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

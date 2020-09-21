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
final class CoinMarketCap implements Exchange
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
        $this->client = Client::new('https://pro-api.coinmarketcap.com/v1/');
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
        $this->client->withHeaders(['X-CMC_PRO_API_KEY' => $token]);
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        return array_map(fn ($symbol) => new Symbol([
            'symbol' => $symbol['symbol'],
            'source' => null,
            'target' => null,
        ]), $this->client->get('cryptocurrency/listings/latest', [
            'limit' => 5000
        ])->json()['data']);
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Symbol $symbol): array
    {
        return [];
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

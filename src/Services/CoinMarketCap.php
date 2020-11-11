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
final class CoinMarketCap implements Service
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
            'symbol'   => $symbol['symbol'],
            'source'   => null,
            'sourceId' => null,
            'target'   => null,
            'targetId' => null,
        ]), $this->client->get('cryptocurrency/listings/latest', [
            'limit' => 5000,
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

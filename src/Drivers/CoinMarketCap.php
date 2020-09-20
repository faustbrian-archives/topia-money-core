<?php

declare(strict_types=1);

/*
 * This file is part of Common Crypto Exchange.
 *
 * (c) KodeKeep <hello@kodekeep.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KodeKeep\CommonCryptoExchange\Drivers;

use KodeKeep\CommonCryptoExchange\Contracts\Exchange;
use KodeKeep\CommonCryptoExchange\Helper\Client;

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
        $this->client = new Client('https://pro-api.coinmarketcap.com/v1/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        $response = $this->client->get('cryptocurrency/listings/latest', [
            'CMC_PRO_API_KEY' => config('services.cmc.token'),
            'limit'           => 5000,
        ])->json();

        return collect($response['data'])->map(fn ($coin) => [
            'name'               => $coin['name'],
            'symbol'             => $coin['symbol'],
            'slug'               => $coin['slug'],
            'rank'               => $coin['cmc_rank'],
            'supply_max'         => $coin['max_supply'] ?? 0,
            'supply_circulating' => $coin['circulating_supply'] ?? 0,
            'supply_total'       => $coin['total_supply'] ?? 0,
        ])->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function historical(string $source, ?string $target): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function price(string $source, ?string $target): string
    {
        return '0';
    }
}

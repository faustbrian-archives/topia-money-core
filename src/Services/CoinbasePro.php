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
final class CoinbasePro implements Service
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
        $this->client = Client::new('https://api.pro.coinbase.com');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        return array_map(fn ($symbol) => new Symbol([
            'symbol' => $symbol['id'],
            'source' => $symbol['base_currency'],
            'target' => $symbol['quote_currency'],
        ]), $this->client->get('products')->json());
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Symbol $symbol): array
    {
        return array_map(fn ($day) => new Rate([
            'date' => Carbon::createFromTimestamp($day[0]),
            'rate' => (string) $day[4],
        ]), $this->client->get("products/{$symbol->source}-{$symbol->target}/candles", [
            'start'       => Carbon::now()->subDays(300)->toIso8601String(),
            'end'         => Carbon::now()->endOfDay()->toIso8601String(),
            'granularity' => 86400,
        ])->json());
    }

    /**
     * {@inheritdoc}
     */
    public function rate(Symbol $symbol): Rate
    {
        $response = $this->client->get("products/{$symbol->source}-{$symbol->target}/ticker")->json();

        return new Rate([
            'date' => Carbon::parse($response['time']),
            'rate' => ResolveScientificNotation::execute((float) $response['price']),
        ]);
    }
}

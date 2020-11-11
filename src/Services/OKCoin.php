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
final class OKCoin implements Service
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
        $this->client = Client::new('https://www.okcoin.com/api/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        return array_map(fn ($symbol) => new Symbol([
            'symbol'   => $symbol['instrument_id'],
            'source'   => $symbol['base_currency'],
            'sourceId' => $symbol['base_currency'],
            'target'   => $symbol['quote_currency'],
            'targetId' => $symbol['quote_currency'],
        ]), $this->client->get('spot/v3/instruments')->json());
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Symbol $symbol): array
    {
        return array_map(fn ($day) => new Rate([
            'date' => Carbon::parse($day[0]),
            'rate' => $day[4],
        ]), $this->client->get("spot/v3/instruments/{$symbol->symbol}/candles", [
            'start'       => Carbon::now()->subDays(200)->toIso8601ZuluString(),
            'end'         => Carbon::now()->endOfDay()->toIso8601ZuluString(),
            'granularity' => 86400,
        ])->json());
    }

    /**
     * {@inheritdoc}
     */
    public function rate(Symbol $symbol): Rate
    {
        $response = $this->client->get("spot/v3/instruments/{$symbol->symbol}/ticker")->json();

        return new Rate([
            'date' => Carbon::now(),
            'rate' => ResolveScientificNotation::execute((float) $response['last']),
        ]);
    }
}

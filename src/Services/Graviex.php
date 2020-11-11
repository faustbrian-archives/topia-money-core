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
final class Graviex implements Service
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
        $this->client = Client::new('https://graviex.net/webapi/v3/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        return array_values(array_map(fn ($symbol) => new Symbol([
            'symbol'   => $symbol['base_unit'].$symbol['quote_unit'],
            'source'   => $symbol['base_unit'],
            'sourceId' => $symbol['base_unit'],
            'target'   => $symbol['quote_unit'],
            'targetId' => $symbol['quote_unit'],
        ]), $this->client->get('tickers.json')->json()));
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Symbol $symbol): array
    {
        $startDate = Carbon::now()->startOfCentury();

        return array_map(fn ($day) => new Rate([
            'date' => Carbon::createFromTimestamp($day[0]),
            'rate' => ResolveScientificNotation::execute((float) $day[4]),
        ]), $this->client->get('k.json', [
            'timestamp' => $startDate->unix(),
            'market'    => $symbol->symbol,
            'limit'     => $startDate->diffInDays(),
            'period'    => 1440,
        ])->json());
    }

    /**
     * {@inheritdoc}
     */
    public function rate(Symbol $symbol): Rate
    {
        $response = $this->client->get("tickers/{$symbol->symbol}.json")->json();

        return new Rate([
            'date' => Carbon::createFromTimestamp($response['at']),
            'rate' => ResolveScientificNotation::execute((float) $response['last']),
        ]);
    }
}

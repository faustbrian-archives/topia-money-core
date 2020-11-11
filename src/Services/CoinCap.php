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
final class CoinCap implements Service
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
        $this->client = Client::new('https://api.coincap.io/v2/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        $offset  = 0;
        $results = [];

        do {
            $response = $this->client->get('markets', [
                'limit'  => 2000,
                'offset' => $offset,
            ])->json();

            $results = array_merge($results, $response['data']);

            $offset += 2000;
        } while (! empty($response['data']));

        return array_map(fn ($symbol) => new Symbol([
            'symbol'   => $symbol['baseSymbol'].'-'.$symbol['quoteSymbol'],
            'source'   => $symbol['baseSymbol'],
            'sourceId' => $symbol['baseId'],
            'target'   => $symbol['quoteSymbol'],
            'targetId' => $symbol['quoteId'],
        ]), $results);
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
        $response = $this->client->get('rates/'.$symbol->source)->json();

        return new Rate([
            'date' => Carbon::createFromTimestampMs($response['timestamp']),
            'rate' => ResolveScientificNotation::execute((float) $response['data']['rateUsd']),
        ]);
    }
}

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
final class Kraken implements Service
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
        $this->client = Client::new('https://api.kraken.com/0/public/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        return array_values(array_map(fn ($symbol) => new Symbol([
            'symbol'   => $symbol['altname'],
            'source'   => $symbol['base'],
            'sourceId' => $symbol['base'],
            'target'   => $symbol['quote'],
            'targetId' => $symbol['quote'],
        ]), $this->client->get('AssetPairs')->json()['result']));
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Symbol $symbol): array
    {
        return array_map(fn ($day) => new Rate([
            'date' => Carbon::createFromTimestamp($day[0]),
            'rate' => $day[4],
        ]), head($this->client->get('OHLC', [
            'pair'     => $symbol->symbol,
            'interval' => 1440,
            'since'    => 0,
        ])->json()['result']));
    }

    /**
     * {@inheritdoc}
     */
    public function rate(Symbol $symbol): Rate
    {
        $response = head($this->client->get('Ticker', ['pair' => $symbol->symbol])->json()['result']);

        return new Rate([
            'date' => Carbon::now(),
            'rate' => ResolveScientificNotation::execute((float) $response['c'][0]),
        ]);
    }
}

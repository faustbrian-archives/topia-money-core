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
final class Bitvavo implements Service
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
        $this->client = Client::new('https://api.bitvavo.com/v2/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        return array_map(fn ($symbol) => new Symbol([
            'symbol'   => $symbol['market'],
            'source'   => $symbol['base'],
            'sourceId' => $symbol['base'],
            'target'   => $symbol['quote'],
            'targetId' => $symbol['quote'],
        ]), $this->client->get('markets')->json());
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Symbol $symbol): array
    {
        return array_map(fn ($day) => new Rate([
            'date' => Carbon::createFromTimestamp($day[0]),
            'rate' => ResolveScientificNotation::execute((float) $day[4]),
        ]), $this->client->get($symbol->symbol.'/candles', [
            'start'    => Carbon::now()->startOfCentury()->valueOf(),
            'end'      => Carbon::now()->endOfDay()->valueOf(),
            'limit'    => 1440,
            'interval' => '1d',
        ])->json());
    }

    /**
     * {@inheritdoc}
     */
    public function rate(Symbol $symbol): Rate
    {
        $response = $this->client->get('ticker/price', [
            'market' => $symbol->symbol,
        ])->json();

        return new Rate([
            'date' => Carbon::now(),
            'rate' => ResolveScientificNotation::execute((float) $response['price']),
        ]);
    }
}

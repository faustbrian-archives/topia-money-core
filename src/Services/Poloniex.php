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
final class Poloniex implements Service
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
        $this->client = Client::new('https://poloniex.com/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        return array_map(fn ($symbol) => new Symbol([
            'symbol'   => $symbol,
            'source'   => null,
            'sourceId' => null,
            'target'   => null,
            'targetId' => null,
        ]), array_keys($this->client->get('public', ['command' => 'returnCurrencies'])->json()));
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Symbol $symbol): array
    {
        $response = $this->client->get('public', [
            'command'      => 'returnChartData',
            'currencyPair' => $symbol->symbol,
            'period'       => 86400,
            'start'        => Carbon::now()->subDecade()->startOfYear()->unix(),
            'end'          => Carbon::now()->endOfDay()->unix(),
        ])->json();

        return array_map(fn ($day) => new Rate([
            'date' => Carbon::createFromTimestamp($day['date']),
            'rate' => ResolveScientificNotation::execute($day['close']),
        ]), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function rate(Symbol $symbol): Rate
    {
        $response = $this->client->get('public', ['command' => 'returnTicker'])->json();

        return new Rate([
            'date' => Carbon::now(),
            'rate' => ResolveScientificNotation::execute((float) $response[$symbol->symbol]['high24hr']),
        ]);
    }
}

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
use KodeKeep\TopiaMoney\Helpers\ResolveScientificNotation;

/**
 * Undocumented class.
 */
final class Poloniex implements Exchange
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
            'symbol' => $symbol,
            'source' => null,
            'target' => null,
        ]), array_keys($this->client->get('public', ['command' => 'returnCurrencies'])));
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
        ]);

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
        $response = $this->client->get('public', ['command' => 'returnTicker']);

        return new Rate([
            'date' => Carbon::now(),
            'rate' => ResolveScientificNotation::execute((float) $response[$symbol->symbol]['high24hr']),
        ]);
    }
}

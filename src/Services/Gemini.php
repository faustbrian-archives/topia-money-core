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
final class Gemini implements Service
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
        $this->client = Client::new('https://api.gemini.com/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        return array_map(fn ($symbol) => new Symbol([
            'symbol'   => $symbol[0],
            'source'   => strtoupper($symbol[1]),
            'sourceId' => $symbol[1],
            'target'   => strtoupper($symbol[2]),
            'targetId' => $symbol[2],
        ]), [
            ['btcusd', 'btc', 'usd'],
            ['btcdai', 'btc', 'dai'],
            ['ethbtc', 'eth', 'btc'],
            ['ethusd', 'eth', 'usd'],
            ['ethdai', 'eth', 'dai'],
            ['bchusd', 'bch', 'usd'],
            ['bchbtc', 'bch', 'btc'],
            ['bcheth', 'bch', 'eth'],
            ['ltcusd', 'ltc', 'usd'],
            ['ltcbtc', 'ltc', 'btc'],
            ['ltceth', 'ltc', 'eth'],
            ['ltcbch', 'ltc', 'bch'],
            ['zecusd', 'zec', 'usd'],
            ['zecbtc', 'zec', 'btc'],
            ['zeceth', 'zec', 'eth'],
            ['zecbch', 'zec', 'bch'],
            ['zecltc', 'zec', 'ltc'],
            ['batusd', 'bat', 'usd'],
            ['batbtc', 'bat', 'btc'],
            ['bateth', 'bat', 'eth'],
            ['linkusd', 'link', 'usd'],
            ['linkbtc', 'link', 'btc'],
            ['linketh', 'link', 'eth'],
            ['daiusd', 'dai', 'usd'],
            ['oxtusd', 'oxt', 'usd'],
            ['oxtbtc', 'oxt', 'btc'],
            ['oxteth', 'oxt', 'eth'],
            ['ampusd', 'amp', 'usd'],
            ['paxgusd', 'paxg', 'usd'],
            ['compusd', 'comp', 'usd'],
            ['mkrusd', 'mkr', 'usd'],
            ['zrxusd', 'zrx', 'usd'],
            ['kncusd', 'knc', 'usd'],
            ['storjusd', 'storj', 'usd'],
            ['manausd', 'mana', 'usd'],
            ['aaveusd', 'aave', 'usd'],
            ['snxusd', 'snx', 'usd'],
            ['yfiusd', 'yfi', 'usd'],
            ['umausd', 'uma', 'usd'],
            ['balusd', 'bal', 'usd'],
            ['crvusd', 'crv', 'usd'],
            ['renusd', 'ren', 'usd'],
            ['uniusd', 'uni', 'usd'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Symbol $symbol): array
    {
        return array_map(fn ($day) => new Rate([
            'date' => Carbon::createFromTimestamp($day[0]),
            'rate' => (string) $day[4],
        ]), $this->client->get("v2/candles/{$symbol->symbol}/1day")->json());
    }

    /**
     * {@inheritdoc}
     */
    public function rate(Symbol $symbol): Rate
    {
        $response = $this->client->get("v1/pubticker/{$symbol->symbol}")->json();

        return new Rate([
            'date' => Carbon::createFromTimestamp($response['volume']['timestamp']),
            'rate' => ResolveScientificNotation::execute((float) $response['last']),
        ]);
    }
}

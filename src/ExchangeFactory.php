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

namespace KodeKeep\TopiaMoney;

use InvalidArgumentException;
use KodeKeep\TopiaMoney\Contracts\Exchange;
use KodeKeep\TopiaMoney\Exchanges\Altilly;
use KodeKeep\TopiaMoney\Exchanges\Binance;
use KodeKeep\TopiaMoney\Exchanges\Bitfinex;
use KodeKeep\TopiaMoney\Exchanges\BitMEX;
use KodeKeep\TopiaMoney\Exchanges\Bitstamp;
use KodeKeep\TopiaMoney\Exchanges\Bittrex;
use KodeKeep\TopiaMoney\Exchanges\CoinCap;
use KodeKeep\TopiaMoney\Exchanges\CoinGecko;
use KodeKeep\TopiaMoney\Exchanges\CoinMarketCap;
use KodeKeep\TopiaMoney\Exchanges\CryptoCompare;
use KodeKeep\TopiaMoney\Exchanges\GDAX;
use KodeKeep\TopiaMoney\Exchanges\Gemini;
use KodeKeep\TopiaMoney\Exchanges\HitBTC;
use KodeKeep\TopiaMoney\Exchanges\Kraken;
use KodeKeep\TopiaMoney\Exchanges\OKCoin;
use KodeKeep\TopiaMoney\Exchanges\Poloniex;

/**
 * Undocumented class.
 */
final class ExchangeFactory
{
    /**
     * Undocumented function.
     *
     * @param string $name
     *
     * @return Exchange
     */
    public static function make(string $name): Exchange
    {
        try {
            return [
                'altilly'       => new Altilly(),
                'binance'       => new Binance(),
                'bitfinex'      => new Bitfinex(),
                'bitmex'        => new BitMEX(),
                'bitstamp'      => new Bitstamp(),
                'bittrex'       => new Bittrex(),
                'coincap'       => new CoinCap(),
                'coingecko'     => new CoinGecko(),
                'coinmarketcap' => new CoinMarketCap(),
                'cryptocompare' => new CryptoCompare(),
                'gdax'          => new GDAX(),
                'gemini'        => new Gemini(),
                'hitbtc'        => new HitBTC(),
                'kraken'        => new Kraken(),
                'okcoin'        => new OKCoin(),
                'poloniex'      => new Poloniex(),
            ][$name];
        } catch (\Throwable $th) {
            throw new InvalidArgumentException("Failed to find a driver for [{$name}].");
        }
    }
}

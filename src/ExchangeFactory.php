<?php

declare(strict_types=1);

/*
 * This file is part of Common Crypto Exchange.
 *
 * (c) KodeKeep <hello@kodekeep.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KodeKeep\CommonCryptoExchange;

use InvalidArgumentException;
use KodeKeep\CommonCryptoExchange\Contracts\Exchange;
use KodeKeep\CommonCryptoExchange\Drivers\Altilly;
use KodeKeep\CommonCryptoExchange\Drivers\Binance;
use KodeKeep\CommonCryptoExchange\Drivers\Bitfinex;
use KodeKeep\CommonCryptoExchange\Drivers\BitMEX;
use KodeKeep\CommonCryptoExchange\Drivers\Bittrex;
use KodeKeep\CommonCryptoExchange\Drivers\CoinCap;
use KodeKeep\CommonCryptoExchange\Drivers\CoinGecko;
use KodeKeep\CommonCryptoExchange\Drivers\CoinMarketCap;
use KodeKeep\CommonCryptoExchange\Drivers\CryptoCompare;
use KodeKeep\CommonCryptoExchange\Drivers\GDAX;
use KodeKeep\CommonCryptoExchange\Drivers\Gemini;
use KodeKeep\CommonCryptoExchange\Drivers\Kraken;
use KodeKeep\CommonCryptoExchange\Drivers\OKCoin;
use KodeKeep\CommonCryptoExchange\Drivers\Poloniex;

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
                'bittrex'       => new Bittrex(),
                'coincap'       => new CoinCap(),
                'coingecko'     => new CoinGecko(),
                'coinmarketcap' => new CoinMarketCap(),
                'cryptocompare' => new CryptoCompare(),
                'gdax'          => new GDAX(),
                'gemini'        => new Gemini(),
                'kraken'        => new Kraken(),
                'okcoin'        => new OKCoin(),
                'poloniex'      => new Poloniex(),
            ][$name];
        } catch (\Throwable $th) {
            throw new InvalidArgumentException("Failed to find a driver for [{$name}].");
        }
    }
}

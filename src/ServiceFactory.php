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

namespace Konceiver\TopiaMoney;

use InvalidArgumentException;
use Konceiver\TopiaMoney\Contracts\Service;
use Konceiver\TopiaMoney\Enums\ServiceEnum;
use Konceiver\TopiaMoney\Services\Altilly;
use Konceiver\TopiaMoney\Services\Binance;
use Konceiver\TopiaMoney\Services\Bitfinex;
use Konceiver\TopiaMoney\Services\BitMEX;
use Konceiver\TopiaMoney\Services\Bitstamp;
use Konceiver\TopiaMoney\Services\Bittrex;
use Konceiver\TopiaMoney\Services\Bitvavo;
use Konceiver\TopiaMoney\Services\CoinbasePro;
use Konceiver\TopiaMoney\Services\CoinCap;
use Konceiver\TopiaMoney\Services\CoinGecko;
use Konceiver\TopiaMoney\Services\CoinMarketCap;
use Konceiver\TopiaMoney\Services\CryptoCompare;
use Konceiver\TopiaMoney\Services\EuropeanCentralBank;
use Konceiver\TopiaMoney\Services\ExchangeRatesAPI;
use Konceiver\TopiaMoney\Services\Fixer;
use Konceiver\TopiaMoney\Services\Frankfurter;
use Konceiver\TopiaMoney\Services\Gemini;
use Konceiver\TopiaMoney\Services\Graviex;
use Konceiver\TopiaMoney\Services\HitBTC;
use Konceiver\TopiaMoney\Services\Kraken;
use Konceiver\TopiaMoney\Services\OKCoin;
use Konceiver\TopiaMoney\Services\OKEx;
use Konceiver\TopiaMoney\Services\Poloniex;
use Konceiver\TopiaMoney\Services\VCC;

/**
 * Undocumented class.
 */
final class ServiceFactory
{
    const SERVICE_CLASSES = [
        ServiceEnum::ALTILLY               => Altilly::class,
        ServiceEnum::BINANCE               => Binance::class,
        ServiceEnum::BITFINEX              => Bitfinex::class,
        ServiceEnum::BITMEX                => BitMEX::class,
        ServiceEnum::BITSTAMP              => Bitstamp::class,
        ServiceEnum::BITTREX               => Bittrex::class,
        ServiceEnum::BITVAVO               => Bitvavo::class,
        ServiceEnum::COINBASE_PRO          => CoinbasePro::class,
        ServiceEnum::COINCAP               => CoinCap::class,
        ServiceEnum::COINGECKO             => CoinGecko::class,
        ServiceEnum::COINMARKETCAP         => CoinMarketCap::class,
        ServiceEnum::CRYPTOCOMPARE         => CryptoCompare::class,
        ServiceEnum::EUROPEAN_CENTRAL_BANK => EuropeanCentralBank::class,
        ServiceEnum::EXCHANGE_RATES_API    => ExchangeRatesAPI::class,
        ServiceEnum::FIXER                 => Fixer::class,
        ServiceEnum::FRANKFURTER           => Frankfurter::class,
        ServiceEnum::GEMINI                => Gemini::class,
        ServiceEnum::GRAVIEX               => Graviex::class,
        ServiceEnum::HITBTC                => HitBTC::class,
        ServiceEnum::KRAKEN                => Kraken::class,
        ServiceEnum::OKCOIN                => OKCoin::class,
        ServiceEnum::OKEX                  => OKEx::class,
        ServiceEnum::POLONIEX              => Poloniex::class,
        ServiceEnum::VCC                   => VCC::class,
    ];

    /**
     * Undocumented function.
     *
     * @param string $name
     *
     * @return Service
     */
    public static function make(string $name): Service
    {
        try {
            $service = static::SERVICE_CLASSES[$name];

            return new $service();
        } catch (\Throwable $th) {
            throw new InvalidArgumentException("Failed to find a driver for [{$name}].");
        }
    }
}

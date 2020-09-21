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
use KodeKeep\TopiaMoney\Contracts\Crawler;
use KodeKeep\TopiaMoney\Contracts\Exchange;
use KodeKeep\TopiaMoney\Crawlers\CoinMarketCap;

/**
 * Undocumented class.
 */
final class CrawlerFactory
{
    /**
     * Undocumented function.
     *
     * @param string $name
     *
     * @return Exchange
     */
    public static function make(string $name): Crawler
    {
        try {
            return [
                'coinmarketcap' => new CoinMarketCap(),
            ][$name];
        } catch (\Throwable $th) {
            throw new InvalidArgumentException("Failed to find a driver for [{$name}].");
        }
    }
}

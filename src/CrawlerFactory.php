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
use Konceiver\TopiaMoney\Contracts\Crawler;
use Konceiver\TopiaMoney\Crawlers\CoinMarketCap;
use Konceiver\TopiaMoney\Enums\CrawlerEnum;

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
     * @return Crawler
     */
    public static function make(string $name): Crawler
    {
        try {
            return [
                CrawlerEnum::COINMARKETCAP => new CoinMarketCap(),
            ][$name];
        } catch (\Throwable $th) {
            throw new InvalidArgumentException("Failed to find a driver for [{$name}].");
        }
    }
}

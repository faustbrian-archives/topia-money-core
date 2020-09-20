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

namespace KodeKeep\CommonCryptoExchange\Contracts;

use KodeKeep\CommonCryptoExchange\DTO\Rate;
use KodeKeep\CommonCryptoExchange\DTO\Ticker;
use KodeKeep\CommonCryptoExchange\DTO\Rate;

interface Exchange
{
    /**
     * Undocumented function.
     *
     * @return Ticker[]
     */
    public function tickers(): array;

    /**
     * Undocumented function.
     *
     * @param Ticker $ticker
     *
     * @return Rate[]
     */
    public function historical(Ticker $ticker): array;

    /**
     * Undocumented function.
     *
     * @param Ticker $ticker
     *
     * @return Rate
     */
    public function price(Ticker $ticker): Rate;
}

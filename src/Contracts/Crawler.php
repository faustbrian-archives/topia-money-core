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

namespace Konceiver\TopiaMoney\Contracts;

use Konceiver\TopiaMoney\DTO\Rate;
use Konceiver\TopiaMoney\DTO\Symbol;

interface Crawler
{
    /**
     * Undocumented function.
     *
     * @param Symbol $symbol
     *
     * @return Rate[]
     */
    public function crawl(Symbol $symbol): array;
}

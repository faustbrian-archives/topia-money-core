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

namespace KodeKeep\TopiaMoney\Contracts;

use KodeKeep\TopiaMoney\DTO\Rate;
use KodeKeep\TopiaMoney\DTO\Symbol;

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

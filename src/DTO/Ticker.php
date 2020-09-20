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

namespace KodeKeep\CommonCryptoExchange\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class Ticker extends DataTransferObject
{
    /**
     * Undocumented variable.
     *
     * @var string
     */
    public string $symbol;

    /**
     * Undocumented variable.
     *
     * @var string
     */
    public string $source;

    /**
     * Undocumented variable.
     *
     * @var string
     */
    public string $target;
}

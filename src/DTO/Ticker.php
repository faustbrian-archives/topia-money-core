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

namespace KodeKeep\CommonCryptoExchange\Enums;

use Spatie\DataTransferObject\DataTransferObject;

class Ticker extends DataTransferObject
{
    public string $symbol;

    public string $source;

    public string $target;
}

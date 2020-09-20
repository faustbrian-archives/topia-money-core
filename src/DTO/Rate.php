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

use Illuminate\Support\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class Rate extends DataTransferObject
{
    public Carbon $date;

    public string $rate;
}

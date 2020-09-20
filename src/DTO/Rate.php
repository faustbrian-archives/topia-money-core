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

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Undocumented class.
 */
final class Rate extends DataTransferObject
{
    /**
     * Undocumented variable.
     *
     * @var Carbon
     */
    public Carbon $date;

    /**
     * Undocumented variable.
     *
     * @var string
     */
    public string $rate;
}

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

namespace KodeKeep\TopiaMoney\DTO;

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
     * @var \Carbon\Carbon
     */
    public Carbon $date;

    /**
     * Undocumented variable.
     *
     * @var string
     */
    public string $rate;
}

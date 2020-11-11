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

namespace Konceiver\TopiaMoney\DTO;

use Carbon\Carbon;

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

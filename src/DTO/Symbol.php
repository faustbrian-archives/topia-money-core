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

use Spatie\DataTransferObject\DataTransferObject;

/**
 * Undocumented class.
 */
final class Symbol extends DataTransferObject
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
     * @var string|null
     */
    public ?string $source;

    /**
     * Undocumented variable.
     *
     * @var string|null
     */
    public ?string $target;
}

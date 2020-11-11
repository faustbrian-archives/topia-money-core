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
    public ?string $sourceId;

    /**
     * Undocumented variable.
     *
     * @var string|null
     */
    public ?string $target;

    /**
     * Undocumented variable.
     *
     * @var string|null
     */
    public ?string $targetId;
}

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

use TypeError;

/**
 * Undocumented class.
 */
abstract class DataTransferObject
{
    /**
     * Create a new DTO instance.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        foreach ($parameters as $key => $value) {
            if (! property_exists($this, $key)) {
                throw new TypeError("Public property `{$key}` not found on ".static::class);
            }

            $this->$key = $value;
        }
    }

    /**
     * Ensure an immutable DTO.
     *
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        throw new TypeError("Cannot change the value of property {$name} on an immutable data transfer object");
    }
}

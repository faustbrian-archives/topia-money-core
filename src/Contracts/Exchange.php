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

namespace KodeKeep\CommonCryptoExchange\Contracts;

interface Exchange
{
    /**
     * Undocumented function.
     *
     * @return array
     */
    public function symbols(): array;

    /**
     * Undocumented function.
     *
     * @param string      $source
     * @param string|null $target
     *
     * @return array
     */
    public function historical(string $source, ?string $target): array;

    /**
     * Undocumented function.
     *
     * @param string      $source
     * @param string|null $target
     *
     * @return string
     */
    public function price(string $source, ?string $target): string;
}

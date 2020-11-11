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

namespace Konceiver\TopiaMoney\Exceptions;

use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * Undocumented class.
 */
final class RateLimitException extends TooManyRequestsHttpException
{
    //
}

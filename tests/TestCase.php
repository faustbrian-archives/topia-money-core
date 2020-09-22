<?php

declare(strict_types=1);

/*
 * This file is part of TopiaMoney.
 *
 * (c) KodeKeep <hello@kodekeep.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KodeKeep\TopiaMoney\Tests;

use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function fakeRequest(string $file)
    {
        Http::fake([
            '*' => Http::response(file_get_contents(__DIR__.'/Unit/Exchanges/fixtures/'.$file.'.json'), 200),
        ]);
    }
}

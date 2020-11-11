<?php

declare(strict_types=1);

/*
 * This file is part of TopiaMoney.
 *
 * (c) Konceiver <info@konceiver.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Konceiver\TopiaMoney\Tests;

use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function fakeRequest(string $file)
    {
        Http::fake([
            '*' => Http::response($this->fixture($file), 200),
        ]);
    }

    protected function fixture(string $file)
    {
        return file_get_contents(__DIR__.'/Unit/Services/fixtures/'.$file.'.json');
    }
}

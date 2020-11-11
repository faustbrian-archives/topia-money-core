<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\BitMEX;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    $this->fakeRequest('bitmex/symbols');

    assertFetchesSymbols(new BitMEX());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('bitmex/historical');

    assertFetchesHistorical(new BitMEX(), new Symbol(['symbol' => 'LSKXBT']));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('bitmex/rate');

    assertFetchesRate(new BitMEX(), new Symbol(['symbol' => 'LSKXBT']));
});

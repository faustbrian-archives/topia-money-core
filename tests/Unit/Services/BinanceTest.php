<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\Binance;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    $this->fakeRequest('binance/symbols');

    assertFetchesSymbols(new Binance());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('binance/historical');

    assertFetchesHistorical(new Binance(), new Symbol(['symbol' => 'HOTETH']));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('binance/rate');

    assertFetchesRate(new Binance(), new Symbol(['symbol' => 'HOTETH']));
});

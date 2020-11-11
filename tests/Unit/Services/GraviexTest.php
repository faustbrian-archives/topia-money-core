<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\Graviex;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    $this->fakeRequest('graviex/symbols');

    assertFetchesSymbols(new Graviex());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('graviex/historical');

    assertFetchesHistorical(new Graviex(), new Symbol(['symbol' => 'giobtc']));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('graviex/rate');

    assertFetchesRate(new Graviex(), new Symbol(['symbol' => 'giobtc']));
});

<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\HitBTC;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    $this->fakeRequest('hitbtc/symbols');

    assertFetchesSymbols(new HitBTC());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('hitbtc/historical');

    assertFetchesHistorical(new HitBTC(), new Symbol(['symbol' => 'SUBETH']));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('hitbtc/rate');

    assertFetchesRate(new HitBTC(), new Symbol(['symbol' => 'SUBETH']));
});

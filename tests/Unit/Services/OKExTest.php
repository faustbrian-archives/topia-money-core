<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\OKEx;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    $this->fakeRequest('okex/symbols');

    assertFetchesSymbols(new OKEx());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('okex/historical');

    assertFetchesHistorical(new OKEx(), new Symbol(['symbol' => 'BCH-EUR']));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('okex/rate');

    assertFetchesRate(new OKEx(), new Symbol(['symbol' => 'BCH-EUR']));
});

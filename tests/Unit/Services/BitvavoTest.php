<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\Bitvavo;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    $this->fakeRequest('bitvavo/symbols');

    assertFetchesSymbols(new Bitvavo());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('bitvavo/historical');

    assertFetchesHistorical(new Bitvavo(), new Symbol(['symbol' => 'BTC-EUR']));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('bitvavo/rate');

    assertFetchesRate(new Bitvavo(), new Symbol(['symbol' => 'BTC-EUR']));
});

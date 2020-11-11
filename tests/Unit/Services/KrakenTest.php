<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\Kraken;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    $this->fakeRequest('kraken/symbols');

    assertFetchesSymbols(new Kraken());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('kraken/historical');

    assertFetchesHistorical(new Kraken(), new Symbol(['symbol' => 'ZUSDZCAD']));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('kraken/rate');

    assertFetchesRate(new Kraken(), new Symbol(['symbol' => 'ZUSDZCAD']));
});

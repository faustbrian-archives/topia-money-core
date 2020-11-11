<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\Altilly;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    $this->fakeRequest('altilly/symbols');

    assertFetchesSymbols(new Altilly());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('altilly/historical');

    assertFetchesHistorical(new Altilly(), new Symbol(['symbol' => 'ETHBTC']));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('altilly/rate');

    assertFetchesRate(new Altilly(), new Symbol(['symbol' => 'ETHBTC']));
});

<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\Frankfurter;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    $this->fakeRequest('exchange-rates-api/symbols');

    assertFetchesSymbols(new Frankfurter());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('exchange-rates-api/historical');

    assertFetchesHistorical(new Frankfurter(), new Symbol([
        'symbol' => 'EUR-USD',
        'source' => 'EUR',
        'target' => 'USD',
    ]));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('exchange-rates-api/rate');

    assertFetchesRate(new Frankfurter(), new Symbol([
        'symbol' => 'EUR-USD',
        'source' => 'EUR',
        'target' => 'USD',
    ]));
});

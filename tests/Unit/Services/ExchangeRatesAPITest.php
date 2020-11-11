<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\ExchangeRatesAPI;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    $this->fakeRequest('exchange-rates-api/symbols');

    assertFetchesSymbols(new ExchangeRatesAPI());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('exchange-rates-api/historical');

    assertFetchesHistorical(new ExchangeRatesAPI(), new Symbol([
        'symbol' => 'EUR-USD',
        'source' => 'EUR',
        'target' => 'USD',
    ]));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('exchange-rates-api/rate');

    assertFetchesRate(new ExchangeRatesAPI(), new Symbol([
        'symbol' => 'EUR-USD',
        'source' => 'EUR',
        'target' => 'USD',
    ]));
});

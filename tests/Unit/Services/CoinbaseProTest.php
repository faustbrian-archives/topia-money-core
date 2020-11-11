<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\CoinbasePro;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    $this->fakeRequest('coinbase-pro/symbols');

    assertFetchesSymbols(new CoinbasePro());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('coinbase-pro/historical');

    assertFetchesHistorical(new CoinbasePro(), new Symbol([
        'symbol' => 'BTC-USD',
        'source' => 'BTC',
        'target' => 'USD',
    ]));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('coinbase-pro/rate');

    assertFetchesRate(new CoinbasePro(), new Symbol([
        'symbol' => 'BTC-USD',
        'source' => 'BTC',
        'target' => 'USD',
    ]));
});

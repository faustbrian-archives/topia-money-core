<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\VCC;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    $this->fakeRequest('vcc/symbols');

    assertFetchesSymbols(new VCC());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('vcc/historical');

    assertFetchesHistorical(new VCC(), new Symbol(['symbol' => 'ETH_BTC']));
})->skip('not available');

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('vcc/rate');

    assertFetchesRate(new VCC(), new Symbol(['symbol' => 'ETH_BTC']));
});

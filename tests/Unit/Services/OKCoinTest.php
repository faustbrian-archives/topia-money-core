<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\OKCoin;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    $this->fakeRequest('okcoin/symbols');

    assertFetchesSymbols(new OKCoin());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('okcoin/historical');

    assertFetchesHistorical(new OKCoin(), new Symbol(['symbol' => 'BCH-EUR']));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('okcoin/rate');

    assertFetchesRate(new OKCoin(), new Symbol(['symbol' => 'BCH-EUR']));
});

<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\Poloniex;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    $this->fakeRequest('poloniex/symbols');

    assertFetchesSymbols(new Poloniex());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('poloniex/historical');

    assertFetchesHistorical(new Poloniex(), new Symbol(['symbol' => 'ETH_COMP']));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('poloniex/rate');

    assertFetchesRate(new Poloniex(), new Symbol(['symbol' => 'ETH_COMP']));
});

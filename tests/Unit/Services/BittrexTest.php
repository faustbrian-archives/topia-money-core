<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\Bittrex;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    $this->fakeRequest('bittrex/symbols');

    assertFetchesSymbols(new Bittrex());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('bittrex/historical');

    assertFetchesHistorical(new Bittrex(), new Symbol(['symbol' => 'USDT-GST']));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('bittrex/rate');

    assertFetchesRate(new Bittrex(), new Symbol(['symbol' => 'USDT-GST']));
});

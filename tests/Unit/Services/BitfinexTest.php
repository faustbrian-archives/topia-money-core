<?php

use Illuminate\Support\Facades\Http;
use Konceiver\TopiaMoney\DTO\Symbol;

use Konceiver\TopiaMoney\Services\Bitfinex;
use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    Http::fakeSequence()
        ->push($this->fixture('bitfinex/symbols-currencies'), 200)
        ->push($this->fixture('bitfinex/symbols'), 200);

    assertFetchesSymbols(new Bitfinex());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('bitfinex/historical');

    assertFetchesHistorical(new Bitfinex(), new Symbol(['symbol' => 'tBTCUSD']));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('bitfinex/rate');

    assertFetchesRate(new Bitfinex(), new Symbol(['symbol' => 'ZRXETH']));
});

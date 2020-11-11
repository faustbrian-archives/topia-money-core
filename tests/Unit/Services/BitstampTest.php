<?php

use Illuminate\Support\Facades\Http;
use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\Bitstamp;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    assertFetchesSymbols(new Bitstamp());
});

it('can fetch the historical rates for the given symbol', function () {
    Http::fakeSequence()
        ->push(file_get_contents(__DIR__.'/fixtures/bitstamp/historical.json'), 200)
        ->push(file_get_contents(__DIR__.'/fixtures/bitstamp/historical-empty.json'), 200);

    assertFetchesHistorical(new Bitstamp(), new Symbol(['symbol' => 'btcusd']));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('bitstamp/rate');

    assertFetchesRate(new Bitstamp(), new Symbol(['symbol' => 'btcusd']));
});

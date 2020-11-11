<?php

use Illuminate\Support\Facades\Http;
use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\CoinCap;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    Http::fakeSequence()
        ->push(file_get_contents(__DIR__.'/fixtures/coincap/symbols.json'), 200)
        ->push(file_get_contents(__DIR__.'/fixtures/coincap/symbols-empty.json'), 200);

    assertFetchesSymbols(new CoinCap());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('coincap/historical');

    assertFetchesHistorical(new CoinCap(), new Symbol(['symbol' => 'bitcoin']));
})->skip('not available');

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('coincap/rate');

    assertFetchesRate(new CoinCap(), new Symbol(['symbol' => 'BTC-USD', 'source' => 'bitcoin']));
});

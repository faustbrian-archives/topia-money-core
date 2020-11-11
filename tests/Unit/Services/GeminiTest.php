<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\Gemini;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    assertFetchesSymbols(new Gemini());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('gemini/historical');

    assertFetchesHistorical(new Gemini(), new Symbol([
        'symbol' => 'btcusd',
        'source' => 'BTC',
        'target' => 'USD',
    ]));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('gemini/rate');

    assertFetchesRate(new Gemini(), new Symbol([
        'symbol' => 'btcusd',
        'source' => 'BTC',
        'target' => 'USD',
    ]));
});

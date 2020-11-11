<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\CoinGecko;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    $this->fakeRequest('coingecko/symbols');

    assertFetchesSymbols(new CoinGecko());
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('coingecko/historical');

    assertFetchesHistorical(new CoinGecko(), new Symbol([
        'symbol' => 'ark',
        'source' => 'ark',
        'target' => 'btc',
    ]));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('coingecko/rate');

    $subject = new CoinGecko();

    assertFetchesRate(new CoinGecko(), new Symbol([
        'symbol' => 'ark',
        'source' => 'ark',
        'target' => 'btc',
    ]));
});

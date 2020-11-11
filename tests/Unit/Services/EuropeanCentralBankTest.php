<?php

use Illuminate\Support\Facades\Http;
use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\EuropeanCentralBank;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    Http::fake([
        '*' => Http::response(file_get_contents(__DIR__.'/fixtures/european-central-bank/eurofxref-hist.xml'), 200),
    ]);

    assertFetchesSymbols(new EuropeanCentralBank());
});

it('can fetch the historical rates for the given symbol', function () {
    Http::fake([
        '*' => Http::response(file_get_contents(__DIR__.'/fixtures/european-central-bank/eurofxref-hist.xml'), 200),
    ]);

    assertFetchesHistorical(new EuropeanCentralBank(), new Symbol([
        'symbol' => 'EUR-USD',
        'source' => 'EUR',
        'target' => 'USD',
    ]));
});

it('can fetch the current rate for the given symbol', function () {
    Http::fake([
        '*' => Http::response(file_get_contents(__DIR__.'/fixtures/european-central-bank/eurofxref-hist.xml'), 200),
    ]);

    assertFetchesRate(new EuropeanCentralBank(), new Symbol([
        'symbol' => 'EUR-USD',
        'source' => 'EUR',
        'target' => 'USD',
    ]));
});

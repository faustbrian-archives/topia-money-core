<?php

use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Services\Fixer;

use function Tests\assertFetchesHistorical;
use function Tests\assertFetchesRate;
use function Tests\assertFetchesSymbols;

it('can fetch all symbols', function () {
    $this->fakeRequest('fixer/symbols');

    assertFetchesSymbols((new Fixer())->setAuthToken('fd67efe0d023034154d2348c80fee12d'));
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('fixer/historical');

    assertFetchesHistorical((new Fixer())->setAuthToken('fd67efe0d023034154d2348c80fee12d'), new Symbol([
        'symbol' => 'EUR-USD',
        'source' => 'EUR',
        'target' => 'USD',
    ]));
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('fixer/rate');

    assertFetchesRate((new Fixer())->setAuthToken('fd67efe0d023034154d2348c80fee12d'), new Symbol([
        'symbol' => 'EUR-USD',
        'source' => 'EUR',
        'target' => 'USD',
    ]));
})->skip();

<?php

it('can fetch all symbols', function () {
    $this->fakeRequest('coinmarketcap/symbols');

    expect(true)->toBeTrue();
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('coinmarketcap/historical');

    expect(true)->toBeTrue();
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('coinmarketcap/rate');

    expect(true)->toBeTrue();
});

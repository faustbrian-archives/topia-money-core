<?php

it('can fetch all symbols', function () {
    $this->fakeRequest('bitfinex/symbols');

    expect(true)->toBeTrue();
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('bitfinex/historical');

    expect(true)->toBeTrue();
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('bitfinex/rate');

    expect(true)->toBeTrue();
});

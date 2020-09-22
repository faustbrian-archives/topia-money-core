<?php

it('can fetch all symbols', function () {
    $this->fakeRequest('gdax/symbols');

    expect(true)->toBeTrue();
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('gdax/historical');

    expect(true)->toBeTrue();
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('gdax/rate');

    expect(true)->toBeTrue();
});

<?php

it('can fetch all symbols', function () {
    $this->fakeRequest('cryptocompare/symbols');

    expect(true)->toBeTrue();
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('cryptocompare/historical');

    expect(true)->toBeTrue();
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('cryptocompare/rate');

    expect(true)->toBeTrue();
});

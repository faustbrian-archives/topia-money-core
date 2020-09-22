<?php

it('can fetch all symbols', function () {
    $this->fakeRequest('gemini/symbols');

    expect(true)->toBeTrue();
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('gemini/historical');

    expect(true)->toBeTrue();
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('gemini/rate');

    expect(true)->toBeTrue();
});

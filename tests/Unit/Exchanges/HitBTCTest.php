<?php

use KodeKeep\TopiaMoney\DTO\Rate;
use KodeKeep\TopiaMoney\DTO\Symbol;
use KodeKeep\TopiaMoney\Exchanges\HitBTC;

it('can fetch all symbols', function () {
    $this->fakeRequest('hitbtc/symbols');

    $subject = new HitBTC();

    expect($response = $subject->symbols())->toBeArray();
    expect($response[0])->toBeInstanceOf(Symbol::class);
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('hitbtc/historical');

    $subject = new HitBTC();

    expect($response = $subject->historical(new Symbol(['symbol' => 'SUBETH'])))->toBeArray();
    expect($response[0])->toBeInstanceOf(Rate::class);
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('hitbtc/rate');

    $subject = new HitBTC();

    expect($subject->rate(new Symbol(['symbol' => 'SUBETH'])))->toBeInstanceOf(Rate::class);
});

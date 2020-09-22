<?php

use KodeKeep\TopiaMoney\DTO\Rate;
use KodeKeep\TopiaMoney\DTO\Symbol;
use KodeKeep\TopiaMoney\Exchanges\Altilly;

it('can fetch all symbols', function () {
    $this->fakeRequest('altilly/symbols');

    $subject = new Altilly();

    expect($response = $subject->symbols())->toBeArray();
    expect($response[0])->toBeInstanceOf(Symbol::class);
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('altilly/historical');

    $subject = new Altilly();

    expect($response = $subject->historical(new Symbol(['symbol' => 'ETHBTC'])))->toBeArray();
    expect($response[0])->toBeInstanceOf(Rate::class);
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('altilly/rate');

    $subject = new Altilly();

    expect($subject->rate(new Symbol(['symbol' => 'ETHBTC'])))->toBeInstanceOf(Rate::class);
});

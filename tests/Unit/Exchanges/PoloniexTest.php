<?php

use KodeKeep\TopiaMoney\DTO\Rate;
use KodeKeep\TopiaMoney\DTO\Symbol;
use KodeKeep\TopiaMoney\Exchanges\Poloniex;

it('can fetch all symbols', function () {
    $this->fakeRequest('poloniex/symbols');

    $subject = new Poloniex();

    expect($response = $subject->symbols())->toBeArray();
    expect($response[0])->toBeInstanceOf(Symbol::class);
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('poloniex/historical');

    $subject = new Poloniex();

    expect($response = $subject->historical(new Symbol(['symbol' => 'ETH_COMP'])))->toBeArray();
    expect($response[0])->toBeInstanceOf(Rate::class);
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('poloniex/rate');

    $subject = new Poloniex();

    expect($subject->rate(new Symbol(['symbol' => 'ETH_COMP'])))->toBeInstanceOf(Rate::class);
});

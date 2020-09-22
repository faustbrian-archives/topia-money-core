<?php

use KodeKeep\TopiaMoney\DTO\Rate;
use KodeKeep\TopiaMoney\DTO\Symbol;
use KodeKeep\TopiaMoney\Exchanges\Bitfinex;

it('can fetch all symbols', function () {
    $this->fakeRequest('bitfinex/symbols');

    $subject = new Bitfinex();

    expect($response = $subject->symbols())->toBeArray();
    expect($response[0])->toBeInstanceOf(Symbol::class);
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('bitfinex/historical');

    $subject = new Bitfinex();

    expect($response = $subject->historical(new Symbol(['symbol' => 'tBTCUSD'])))->toBeArray();
    expect($response[0])->toBeInstanceOf(Rate::class);
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('bitfinex/rate');

    $subject = new Bitfinex();

    expect($subject->rate(new Symbol(['symbol' => 'ZRXETH'])))->toBeInstanceOf(Rate::class);
});

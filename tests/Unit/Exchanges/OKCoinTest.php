<?php

use KodeKeep\TopiaMoney\DTO\Rate;
use KodeKeep\TopiaMoney\DTO\Symbol;
use KodeKeep\TopiaMoney\Exchanges\OKCoin;

it('can fetch all symbols', function () {
    $subject = new OKCoin();

    expect($response = $subject->symbols())->toBeArray();
    expect($response[0])->toBeInstanceOf(Symbol::class);
});

it('can fetch the historical rates for the given symbol', function () {
    $subject = new OKCoin();

    expect($subject->historical(new Symbol(['symbol' => 'BCH-EUR'])))->toBeArray();
});

it('can fetch the current rate for the given symbol', function () {
    $subject = new OKCoin();

    expect($subject->rate(new Symbol(['symbol' => 'BCH-EUR'])))->toBeInstanceOf(Rate::class);
});

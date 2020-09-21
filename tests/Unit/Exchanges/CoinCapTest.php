<?php

use KodeKeep\TopiaMoney\DTO\Rate;
use KodeKeep\TopiaMoney\DTO\Symbol;
use KodeKeep\TopiaMoney\Exchanges\CoinCap;

it('can fetch all symbols', function () {
    $subject = new CoinCap();

    expect($response = $subject->symbols())->toBeArray();
    expect($response[0])->toBeInstanceOf(Symbol::class);
});

it('can fetch the historical rates for the given symbol', function () {
    $subject = new CoinCap();

    expect($subject->historical(new Symbol(['symbol' => 'bitcoin'])))->toBeArray();
});

it('can fetch the current rate for the given symbol', function () {
    $subject = new CoinCap();

    expect($subject->rate(new Symbol(['symbol' => 'BTC-USD', 'source' => 'bitcoin'])))->toBeInstanceOf(Rate::class);
});

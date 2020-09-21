<?php

use KodeKeep\TopiaMoney\DTO\Rate;
use KodeKeep\TopiaMoney\DTO\Symbol;
use KodeKeep\TopiaMoney\Exchanges\Bitfinex;

it('can fetch all symbols', function () {
    $subject = new Bitfinex();

    expect($response = $subject->symbols())->toBeArray();
    expect($response[0])->toBeInstanceOf(Symbol::class);
});

it('can fetch the historical rates for the given symbol', function () {
    $subject = new Bitfinex();

    expect($subject->historical(new Symbol(['symbol' => 'ZRXETH'])))->toBeArray();
});

it('can fetch the current rate for the given symbol', function () {
    $subject = new Bitfinex();

    expect($subject->rate(new Symbol(['symbol' => 'ZRXETH'])))->toBeInstanceOf(Rate::class);
});

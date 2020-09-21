<?php

use KodeKeep\TopiaMoney\DTO\Rate;
use KodeKeep\TopiaMoney\DTO\Symbol;
use KodeKeep\TopiaMoney\Exchanges\Bitstamp;

it('can fetch all symbols', function () {
    $subject = new Bitstamp();

    expect($response = $subject->symbols())->toBeArray();
});

it('can fetch the historical rates for the given symbol', function () {
    $subject = new Bitstamp();

    expect($response = $subject->historical(new Symbol(['symbol' => 'btcusd'])))->toBeArray();
    expect($response[0])->toBeInstanceOf(Rate::class);
});

it('can fetch the current rate for the given symbol', function () {
    $subject = new Bitstamp();

    expect($subject->rate(new Symbol(['symbol' => 'btcusd'])))->toBeInstanceOf(Rate::class);
});

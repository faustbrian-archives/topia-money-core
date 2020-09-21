<?php

use KodeKeep\TopiaMoney\DTO\Rate;
use KodeKeep\TopiaMoney\DTO\Symbol;
use KodeKeep\TopiaMoney\Exchanges\Kraken;

it('can fetch all symbols', function () {
    $subject = new Kraken();

    expect($response = $subject->symbols())->toBeArray();
    expect($response[0])->toBeInstanceOf(Symbol::class);
});

it('can fetch the historical rates for the given symbol', function () {
    $subject = new Kraken();

    expect($response = $subject->historical(new Symbol(['symbol' => 'ZUSDZCAD'])))->toBeArray();
    expect($response[0])->toBeInstanceOf(Rate::class);
});

it('can fetch the current rate for the given symbol', function () {
    $subject = new Kraken();

    expect($subject->rate(new Symbol(['symbol' => 'ZUSDZCAD'])))->toBeInstanceOf(Rate::class);
});

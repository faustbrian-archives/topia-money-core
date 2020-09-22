<?php

use Illuminate\Support\Facades\Http;
use KodeKeep\TopiaMoney\DTO\Rate;
use KodeKeep\TopiaMoney\DTO\Symbol;
use KodeKeep\TopiaMoney\Exchanges\CoinCap;

it('can fetch all symbols', function () {
    Http::fakeSequence()
        ->push(file_get_contents(__DIR__.'/fixtures/coincap/symbols.json'), 200)
        ->push(file_get_contents(__DIR__.'/fixtures/coincap/symbols-empty.json'), 200);

    $subject = new CoinCap();

    expect($response = $subject->symbols())->toBeArray();
    expect($response[0])->toBeInstanceOf(Symbol::class);
});

it('can fetch the historical rates for the given symbol', function () {
    $this->fakeRequest('coincap/historical');

    $subject = new CoinCap();

    expect($subject->historical(new Symbol(['symbol' => 'bitcoin'])))->toBeArray();
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('coincap/rate');

    $subject = new CoinCap();

    expect($subject->rate(new Symbol(['symbol' => 'BTC-USD', 'source' => 'bitcoin'])))->toBeInstanceOf(Rate::class);
});

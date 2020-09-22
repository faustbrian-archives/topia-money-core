<?php

use Illuminate\Support\Facades\Http;
use KodeKeep\TopiaMoney\DTO\Rate;
use KodeKeep\TopiaMoney\DTO\Symbol;
use KodeKeep\TopiaMoney\Exchanges\Bitstamp;

it('can fetch all symbols', function () {
    $this->fakeRequest('bitstamp/symbols');

    $subject = new Bitstamp();

    expect($subject->symbols())->toBeArray();
});

it('can fetch the historical rates for the given symbol', function () {
    Http::fakeSequence()
        ->push(file_get_contents(__DIR__.'/fixtures/bitstamp/historical.json'), 200)
        ->push(file_get_contents(__DIR__.'/fixtures/bitstamp/historical-empty.json'), 200);

    $subject = new Bitstamp();

    expect($response = $subject->historical(new Symbol(['symbol' => 'btcusd'])))->toBeArray();
    expect($response[0])->toBeInstanceOf(Rate::class);
});

it('can fetch the current rate for the given symbol', function () {
    $this->fakeRequest('bitstamp/rate');

    $subject = new Bitstamp();

    expect($subject->rate(new Symbol(['symbol' => 'btcusd'])))->toBeInstanceOf(Rate::class);
});

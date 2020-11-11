<?php

namespace Tests;

use Konceiver\TopiaMoney\Contracts\Service;
use Konceiver\TopiaMoney\DTO\Rate;
use Konceiver\TopiaMoney\DTO\Symbol;
use function Spatie\Snapshots\assertMatchesSnapshot;

function assertFetchesSymbols(Service $service)
{
    $actual = $service->symbols();

    expect($actual)->toBeArray();
    expect($actual[0])->toBeInstanceOf(Symbol::class);

    assertMatchesSnapshot($actual);
}

function assertFetchesHistorical(Service $service, Symbol $symbol)
{
    $actual = $service->historical($symbol);

    expect($actual)->toBeArray();
    expect($actual[0])->toBeInstanceOf(Rate::class);

    assertMatchesSnapshot($actual);
}

function assertFetchesRate(Service $service, Symbol $symbol)
{
    $actual = $service->rate($symbol);

    expect($actual)->toBeInstanceOf(Rate::class);

    assertMatchesSnapshot($actual->rate);
}

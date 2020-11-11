<?php

use Konceiver\TopiaMoney\CrawlerFactory;
use Konceiver\TopiaMoney\Crawlers\CoinMarketCap;
use Konceiver\TopiaMoney\Enums\CrawlerEnum;

it('should create a crawler', function ($name, $class) {
    expect(CrawlerFactory::make($name))->toBeInstanceOf($class);
})->with([
    [CrawlerEnum::COINMARKETCAP, CoinMarketCap::class],
]);

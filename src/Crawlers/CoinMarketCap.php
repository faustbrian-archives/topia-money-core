<?php

declare(strict_types=1);

/*
 * This file is part of Topia.Money.
 *
 * (c) Konceiver <info@konceiver.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Konceiver\TopiaMoney\Crawlers;

use Carbon\Carbon;
use Konceiver\TopiaMoney\Contracts\Crawler;
use Konceiver\TopiaMoney\DTO\Rate;
use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Helpers\Client;
use PHPHtmlParser\Dom;

/**
 * Undocumented class.
 */
final class CoinMarketCap implements Crawler
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * Undocumented function.
     */
    public function __construct()
    {
        $this->client = Client::new('https://coinmarketcap.com/currencies/');
    }

    /**
     * {@inheritdoc}
     */
    public function crawl(Symbol $symbol): array
    {
        $response = $this->client->get("{$symbol->symbol}/historical-data", [
            'start' => Carbon::now()->subDecade()->format('Ymd'),
            'end'   => Carbon::now()->endOfDay()->format('Ymd'),
        ])->body();

        $results = [];

        $dom = new Dom();
        $dom->loadStr($response);
        foreach ($dom->find('.cmc-table-row') as $row) {
            $cells = $row->find('.cmc-table__cell');

            $results[] = new Rate([
                'date'  => Carbon::parse($cells[0]->innerText),
                'close' => $cells[4]->innerText,
            ]);
        }

        return $results;
    }
}

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

namespace Konceiver\TopiaMoney\Services;

use Carbon\Carbon;
use Konceiver\TopiaMoney\Contracts\Service;
use Konceiver\TopiaMoney\DTO\Rate;
use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Helpers\Client;
use Konceiver\TopiaMoney\Helpers\ResolveScientificNotation;
use Spatie\Regex\Regex;

/**
 * Undocumented class.
 */
final class Bitfinex implements Service
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
        $this->client = Client::new('https://api.bitfinex.com/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        $client      = Client::new('https://api-pub.bitfinex.com/v2');
        $currencies  = $client->get('conf/pub:list:currency')->json()[0];
        $marketPairs = $client->get('conf/pub:list:pair:exchange')->json()[0];

        return array_map(function ($symbol) use ($currencies) {
            $matches = Regex::matchAll('/('.implode('|', $currencies).')/', $symbol)->results();

            return new Symbol([
                'symbol'   => $symbol,
                'source'   => $matches[0]->result(),
                'sourceId' => $matches[0]->result(),
                'target'   => $matches[1]->result(),
                'targetId' => $matches[1]->result(),
            ]);
        }, $marketPairs);
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Symbol $symbol): array
    {
        return array_map(fn ($day) => new Rate([
            'date' => Carbon::createFromTimestampMs($day[0]),
            'rate' => ResolveScientificNotation::execute((float) $day[2]),
        ]), $this->client->get("v2/candles/trade:1D:{$symbol->symbol}/hist", [
            'limit' => 10000,
        ])->json());
    }

    /**
     * {@inheritdoc}
     */
    public function rate(Symbol $symbol): Rate
    {
        $response = $this->client->get("v1/pubticker/{$symbol->symbol}")->json();

        return new Rate([
            'date' => Carbon::now(),
            'rate' => ResolveScientificNotation::execute((float) $response['last_price']),
        ]);
    }

    private function explodeMany(array $delimiters, string $string): array
    {
        return explode(chr(1), str_replace($delimiters, chr(1), $string));
    }
}

<?php

declare(strict_types=1);

/*
 * This file is part of Common Crypto Exchange.
 *
 * (c) KodeKeep <hello@kodekeep.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KodeKeep\CommonCryptoExchange\Drivers;

use KodeKeep\CommonCryptoExchange\Contracts\Exchange;
use KodeKeep\CommonCryptoExchange\Helper\Client;
use KodeKeep\CommonCryptoExchange\Helpers\ResolveScientificNotation;

final class Bitfinex implements Exchange
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
        $this->client = new Client('https://api.bitfinex.com/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        $response = $this->client->get('v2/conf/pub:list:pair:exchange')->json();

        return array_map(fn ($symbol) => [
            'symbol' => $symbol,
            'source' => $symbol, // TODO
            'target' => $symbol, // TODO
        ], $response[0]);
    }

    /**
     * {@inheritdoc}
     */
    public function historical(string $source, ?string $target): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function price(string $source, ?string $target): string
    {
        $response = $this->client->get("v1/pubticker/{$source}")->json();

        return ResolveScientificNotation::execute($response['last_price']);
    }
}

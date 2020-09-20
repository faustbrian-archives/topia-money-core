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

final class Gemini implements Exchange
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
        $this->client = new Client('https://api.gemini.com/v1/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        $response = $this->client->get('symbols')->json();

        return collect($response)->transform(fn ($symbol) => [
            'symbol' => $symbol,
            'source' => null,
            'target' => null,
        ])->toArray();
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
        $response = $this->client->get("pubticker/{$this->sourceCurrency}{$this->targetCurrency}")->json();

        return ResolveScientificNotation::execute($response['last']);
    }
}

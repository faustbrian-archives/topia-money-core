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

namespace KodeKeep\CommonCryptoExchange\Helper;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class Client
{
    /**
     * @var \Illuminate\Http\Client\PendingRequest
     */
    protected PendingRequest $client;

    /**
     * Undocumented function.
     */
    public function __construct(string $baseUrl)
    {
        $this->client = Http::baseUrl($baseUrl);
    }

    /**
     * Undocumented function.
     *
     * @param array $headers
     *
     * @return void
     */
    public function withHeaders(array $headers): void
    {
        $this->client->withHeaders($headers);
    }

    /**
     * Undocumented function.
     *
     * @param string $path
     * @param array  $query
     *
     * @return Response
     */
    public function get(string $path, array $query = []): Response
    {
        $response = $this->client->get($path, $query);

        $response->throw();

        return $response;
    }
}

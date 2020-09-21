<?php

declare(strict_types=1);

/*
 * This file is part of Topia.Money.
 *
 * (c) KodeKeep <hello@kodekeep.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KodeKeep\TopiaMoney\Helpers;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * Undocumented class.
 */
final class Client
{
    /**
     * @var PendingRequest
     */
    protected PendingRequest $client;

    /**
     * Undocumented function.
     */
    private function __construct(string $baseUrl)
    {
        $this->client = Http::baseUrl($baseUrl);
    }

    /**
     * Undocumented function.
     */
    public static function new(string $baseUrl)
    {
        return new static($baseUrl);
    }

    /**
     * Undocumented function.
     *
     * @param array $headers
     *
     * @return self
     */
    public function withHeaders(array $headers): self
    {
        $this->client->withHeaders($headers);

        return $this;
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
        return $this->client->get($path, $query);
    }
}

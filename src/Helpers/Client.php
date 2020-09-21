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

use GrahamCampbell\GuzzleFactory\GuzzleFactory;
use Illuminate\Http\Client\Response;

/**
 * Undocumented class.
 */
final class Client
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected \GuzzleHttp\Client $client;

    /**
     * @var array
     */
    protected array $headers = [
        'Accept'       => 'application/json',
        'Content-Type' => 'application/json',
    ];

    /**
     * Undocumented function.
     */
    private function __construct(string $baseUrl)
    {
        $this->client = GuzzleFactory::make(['base_uri' => $baseUrl]);
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
        $this->headers = array_merge($this->headers, $headers);

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
    public function get(string $path, array $query = []): array
    {
        return json_decode($this->client->get($path, [
            'query'   => $query,
            'headers' => $this->headers,
        ])->getBody()->getContents(), true);
    }
}

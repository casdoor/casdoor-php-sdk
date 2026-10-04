<?php

// Copyright 2024 The Casdoor Authors. All Rights Reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

declare(strict_types=1);

namespace Casdoor\Util;

use Casdoor\Exceptions\CasdoorException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HttpClient
{
    private Client $client;
    private string $clientId;
    private string $clientSecret;
    private string $accessToken;
    /** @var array<string, string> */
    private array $headers;

    /**
     * @param string                $accessToken when set, the requests are made as the user who owns it (Authorization: Bearer)
     * @param array<string, string> $headers     the headers added to all the requests, e.g. Accept-Language
     */
    public function __construct(string $clientId, string $clientSecret, string $accessToken = '', array $headers = [], ?Client $client = null)
    {
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->accessToken  = $accessToken;
        $this->headers      = $headers;
        $this->client       = $client ?? new Client();
    }

    private function options(array $options = []): array
    {
        $headers = array_merge($this->headers, $options['headers'] ?? []);
        if ($this->accessToken !== '') {
            $headers['Authorization'] = 'Bearer ' . $this->accessToken;
        } else {
            $options['auth'] = [$this->clientId, $this->clientSecret];
        }
        $options['headers'] = $headers;
        // Casdoor returns its JSON error response with 403 when the caller has no permission
        $options['http_errors'] = false;
        return $options;
    }

    /**
     * Sends the request and returns the raw response body, like DoGetBytesRaw() and DoPostBytesRaw() of casdoor-go-sdk.
     */
    public function request(string $method, string $url, array $options = []): string
    {
        try {
            $response = $this->client->request($method, $url, $this->options($options));
        } catch (GuzzleException $e) {
            throw new CasdoorException($e->getMessage(), $e->getCode(), $e);
        }
        $body   = (string) $response->getBody();
        $status = $response->getStatusCode();
        if ($status !== 200 && $status !== 403) {
            throw new CasdoorException(sprintf('status code: %d, body: %s', $status, $body), $status);
        }
        return $body;
    }

    private function decode(string $body): array
    {
        $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        if (isset($data['status']) && $data['status'] !== 'ok') {
            throw new CasdoorException($data['msg'] ?? 'Unknown error');
        }
        return $data;
    }

    public function get(string $url): array
    {
        return $this->decode($this->request('GET', $url));
    }

    public function post(string $url, mixed $postData, bool $isForm = false, bool $isFile = false): array
    {
        $options = [];

        if ($isForm) {
            if ($isFile) {
                $options['multipart'] = [
                    [
                        'name'     => 'file',
                        'contents' => $postData,
                        'filename' => 'file',
                    ],
                ];
            } else {
                $params = is_string($postData) ? json_decode($postData, true, 512, JSON_THROW_ON_ERROR) : (array) $postData;
                $options['multipart'] = array_map(
                    fn($k, $v) => ['name' => $k, 'contents' => (string) $v],
                    array_keys($params),
                    array_values($params)
                );
            }
        } else {
            $options['body']    = is_string($postData) ? $postData : json_encode($postData, JSON_THROW_ON_ERROR);
            $options['headers'] = ['Content-Type' => 'text/plain;charset=UTF-8'];
        }

        return $this->decode($this->request('POST', $url, $options));
    }
}

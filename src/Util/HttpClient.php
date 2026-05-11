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

    public function __construct(string $clientId, string $clientSecret)
    {
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->client       = new Client();
    }

    public function get(string $url): array
    {
        try {
            $response = $this->client->request('GET', $url, [
                'auth' => [$this->clientId, $this->clientSecret],
            ]);
        } catch (GuzzleException $e) {
            throw new CasdoorException($e->getMessage(), $e->getCode(), $e);
        }

        $body = (string) $response->getBody();
        $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        if (isset($data['status']) && $data['status'] !== 'ok') {
            throw new CasdoorException($data['msg'] ?? 'Unknown error');
        }

        return $data;
    }

    public function post(string $url, mixed $postData, bool $isForm = false, bool $isFile = false): array
    {
        $options = [
            'auth' => [$this->clientId, $this->clientSecret],
        ];

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

        try {
            $response = $this->client->request('POST', $url, $options);
        } catch (GuzzleException $e) {
            throw new CasdoorException($e->getMessage(), $e->getCode(), $e);
        }

        $body = (string) $response->getBody();
        $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        if (isset($data['status']) && $data['status'] !== 'ok') {
            throw new CasdoorException($data['msg'] ?? 'Unknown error');
        }

        return $data;
    }
}

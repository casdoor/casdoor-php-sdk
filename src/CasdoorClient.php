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

namespace Casdoor;

use Casdoor\Util\HttpClient;

class CasdoorClient
{
    public string $endpoint;
    public string $clientId;
    public string $clientSecret;
    public string $certificate;
    public string $organizationName;
    public string $applicationName;
    /** @var array<string, string> the HTTP headers added to all the API requests, e.g. Accept-Language */
    public array $customHeaders = [];
    /** when set by withAccessToken(), the APIs are called as the user who owns the access token */
    public string $accessToken = '';

    private HttpClient $http;

    public function __construct(
        string $endpoint,
        string $clientId,
        string $clientSecret,
        string $certificate,
        string $organizationName,
        string $applicationName,
        array $customHeaders = []
    ) {
        $this->endpoint         = rtrim($endpoint, '/');
        $this->clientId         = $clientId;
        $this->clientSecret     = $clientSecret;
        $this->certificate      = $certificate;
        $this->organizationName = $organizationName;
        $this->applicationName  = $applicationName;
        $this->customHeaders    = $customHeaders;
        $this->http             = new HttpClient($clientId, $clientSecret, '', $customHeaders);
    }

    /**
     * Returns a new client that calls the APIs as the user who owns the access token
     * (Authorization: Bearer) instead of as the application. The current client is not changed.
     */
    public function withAccessToken(string $accessToken): static
    {
        $client              = clone $this;
        $client->accessToken = $accessToken;
        $client->http        = new HttpClient($this->clientId, $this->clientSecret, $accessToken, $this->customHeaders);
        return $client;
    }

    /**
     * Sets the HTTP headers added to all the API requests.
     *
     * @param array<string, string> $headers
     */
    public function setCustomHeaders(array $headers): void
    {
        $this->customHeaders = $headers;
        $this->http          = new HttpClient($this->clientId, $this->clientSecret, $this->accessToken, $headers);
    }

    public function getUrl(string $action, array $queryMap = []): string
    {
        $query = http_build_query($queryMap);
        $url   = sprintf('%s/api/%s', $this->endpoint, $action);
        if ($query !== '') {
            $url .= '?' . $query;
        }
        return $url;
    }

    /**
     * Returns name as is if it's already an "owner/name" ID, otherwise prefixes it with the client's organization.
     */
    public function getId(string $name): string
    {
        return str_contains($name, '/') ? $name : $this->organizationName . '/' . $name;
    }

    /**
     * getId() for the object types that are owned by "admin" instead of an organization.
     */
    public function getAdminId(string $name): string
    {
        return str_contains($name, '/') ? $name : 'admin/' . $name;
    }

    /**
     * Keeps the caller-provided owner and only falls back to $defaultOwner when it's empty.
     */
    public function getOwner(string $owner, string $defaultOwner): string
    {
        return $owner !== '' ? $owner : $defaultOwner;
    }

    /**
     * Returns the raw response body of a GET request.
     */
    public function doGetBytesRaw(string $url): string
    {
        return $this->http->request('GET', $url);
    }

    /**
     * Returns the raw response body of a POST request.
     */
    public function doPostBytesRaw(string $url, string $contentType, string $body): string
    {
        return $this->http->request('POST', $url, [
            'headers' => ['Content-Type' => $contentType !== '' ? $contentType : 'text/plain;charset=UTF-8'],
            'body'    => $body,
        ]);
    }

    public function doGetResponse(string $url): array
    {
        return $this->http->get($url);
    }

    public function doGetBytes(string $url): mixed
    {
        $response = $this->http->get($url);
        return $response['data'];
    }

    public function doPost(string $action, array $queryMap, mixed $postData, bool $isForm = false, bool $isFile = false): array
    {
        $url = $this->getUrl($action, $queryMap);
        return $this->http->post($url, $postData, $isForm, $isFile);
    }

    protected function modifyEntity(string $action, string $id, mixed $entity, ?array $columns): array
    {
        $queryMap = ['id' => $id];
        if (!empty($columns)) {
            $queryMap['columns'] = implode(',', $columns);
        }
        $postData = json_encode($entity, JSON_THROW_ON_ERROR);
        return $this->doPost($action, $queryMap, $postData);
    }

    protected function boolFromResponse(array $response): bool
    {
        return ($response['data'] ?? null) === 'Affected';
    }
}

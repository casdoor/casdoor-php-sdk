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

    private HttpClient $http;

    public function __construct(
        string $endpoint,
        string $clientId,
        string $clientSecret,
        string $certificate,
        string $organizationName,
        string $applicationName
    ) {
        $this->endpoint         = rtrim($endpoint, '/');
        $this->clientId         = $clientId;
        $this->clientSecret     = $clientSecret;
        $this->certificate      = $certificate;
        $this->organizationName = $organizationName;
        $this->applicationName  = $applicationName;
        $this->http             = new HttpClient($clientId, $clientSecret);
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

    public function getId(string $name): string
    {
        return $this->organizationName . '/' . $name;
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

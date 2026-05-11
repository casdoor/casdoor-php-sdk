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

// Provider has the same definition as https://github.com/casdoor/casdoor/blob/master/object/provider.go
class Provider
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';

    public string $displayName       = '';
    public string $category          = '';
    public string $type              = '';
    public string $subType           = '';
    public string $method            = '';
    public string $clientId          = '';
    public string $clientSecret      = '';
    public string $clientId2         = '';
    public string $clientSecret2     = '';
    public string $cert              = '';
    public string $customAuthUrl     = '';
    public string $customTokenUrl    = '';
    public string $customUserInfoUrl = '';
    public string $customLogo        = '';
    public string $scopes            = '';
    public array  $userMapping       = [];

    public string $host       = '';
    public int    $port       = 0;
    public bool   $disableSsl = false;
    public string $title      = '';
    public string $content    = '';
    public string $receiver   = '';

    public string $regionId     = '';
    public string $signName     = '';
    public string $templateCode = '';
    public string $appId        = '';

    public string $endpoint         = '';
    public string $intranetEndpoint = '';
    public string $domain           = '';
    public string $bucket           = '';
    public string $pathPrefix       = '';

    public string $metadata               = '';
    public string $idP                    = '';
    public string $issuerUrl              = '';
    public bool   $enableSignAuthnRequest = false;

    public string $providerUrl = '';
}

trait ProviderTrait
{
    public function getProviders(): array
    {
        $url = $this->getUrl('get-providers', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getPaginationProviders(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-providers', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getProvider(string $name): ?array
    {
        $url = $this->getUrl('get-provider', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function addProvider(Provider $provider): bool
    {
        return $this->modifyProvider('add-provider', $provider);
    }

    public function updateProvider(Provider $provider): bool
    {
        return $this->modifyProvider('update-provider', $provider);
    }

    public function deleteProvider(Provider $provider): bool
    {
        return $this->modifyProvider('delete-provider', $provider);
    }

    private function modifyProvider(string $action, Provider $provider): bool
    {
        $provider->owner = $this->organizationName;
        $queryMap        = ['id' => $provider->owner . '/' . $provider->name];
        $postData        = json_encode($provider, JSON_THROW_ON_ERROR);
        $response        = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

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

// Enforcer has the same definition as https://github.com/casdoor/casdoor/blob/master/object/enforcer.go
class Enforcer
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';
    public string $updatedTime = '';
    public string $displayName = '';
    public string $description = '';

    public string $model   = '';
    public string $adapter = '';
    public bool   $isEnabled = false;
}

trait EnforcerTrait
{
    public function getEnforcers(): array
    {
        $url = $this->getUrl('get-enforcers', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getPaginationEnforcers(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-enforcers', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getEnforcer(string $name): ?array
    {
        $url = $this->getUrl('get-enforcer', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function addEnforcer(Enforcer $enforcer): bool
    {
        return $this->modifyEnforcer('add-enforcer', $enforcer);
    }

    public function updateEnforcer(Enforcer $enforcer): bool
    {
        return $this->modifyEnforcer('update-enforcer', $enforcer);
    }

    public function deleteEnforcer(Enforcer $enforcer): bool
    {
        return $this->modifyEnforcer('delete-enforcer', $enforcer);
    }

    private function modifyEnforcer(string $action, Enforcer $enforcer): bool
    {
        $enforcer->owner = $this->organizationName;
        $queryMap        = ['id' => $enforcer->owner . '/' . $enforcer->name];
        $postData        = json_encode($enforcer, JSON_THROW_ON_ERROR);
        $response        = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

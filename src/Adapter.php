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

// Adapter has the same definition as https://github.com/casdoor/casdoor/blob/master/object/adapter.go
class Adapter
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';

    public bool   $useSameDb       = false;
    public string $type            = '';
    public string $databaseType    = '';
    public string $host            = '';
    public int    $port            = 0;
    public string $user            = '';
    public string $password        = '';
    public string $database        = '';
    public string $table           = '';
    public string $tableNamePrefix = '';

    public bool $isEnabled = false;
}

trait AdapterTrait
{
    public function getAdapters(): array
    {
        $url = $this->getUrl('get-adapters', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getPaginationAdapters(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-adapters', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getAdapter(string $name): ?array
    {
        $url = $this->getUrl('get-adapter', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function addAdapter(Adapter $adapter): bool
    {
        return $this->modifyAdapter('add-adapter', $adapter);
    }

    public function updateAdapter(Adapter $adapter): bool
    {
        return $this->modifyAdapter('update-adapter', $adapter);
    }

    public function deleteAdapter(Adapter $adapter): bool
    {
        return $this->modifyAdapter('delete-adapter', $adapter);
    }

    private function modifyAdapter(string $action, Adapter $adapter): bool
    {
        $adapter->owner = $this->organizationName;
        $queryMap       = ['id' => $adapter->owner . '/' . $adapter->name];
        $postData       = json_encode($adapter, JSON_THROW_ON_ERROR);
        $response       = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

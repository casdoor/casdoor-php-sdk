<?php
// Copyright 2026 The Casdoor Authors. All Rights Reserved.
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

// Record has the same definition as Record of casdoor-go-sdk
class Record
{
    public int $id = 0;
    public string $owner = '';
    public string $name = '';
    public string $createdTime = '';
    public string $organization = '';
    public string $clientIp = '';
    public string $user = '';
    public string $method = '';
    public string $requestUri = '';
    public string $action = '';
    public string $language = '';
    public string $object = '';
    public string $response = '';
    public int $statusCode = 0;
    public string $detail = '';
    public bool $isTriggered = false;
}

trait RecordTrait
{
    public function getRecords(): array
    {
        return $this->doGetBytes($this->getUrl('get-records', ['owner' => $this->organizationName]));
    }

    public function getPaginationRecords(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $response             = $this->doGetResponse($this->getUrl('get-records', $queryMap));
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    /**
     * Gets a record by name, or null if it doesn't exist. Casdoor has no API to get a single record,
     * so it searches the records by name. Like the other APIs that read records, it needs the access
     * token of an admin user, see withAccessToken().
     */
    public function getRecord(string $name): ?array
    {
        $parts = explode('/', $name);
        $name  = end($parts);

        // the name filter matches the records whose names contain the given name
        [$records] = $this->getPaginationRecords(1, 100, ['field' => 'name', 'value' => $name]);
        foreach ($records ?? [] as $record) {
            if (($record['name'] ?? '') === $name) {
                return $record;
            }
        }
        return null;
    }

    public function addRecord(Record $record): bool
    {
        $record->owner        = $this->getOwner($record->owner, $this->organizationName);
        $record->organization = $this->getOwner($record->organization, $this->organizationName);
        $postData             = json_encode($record, JSON_THROW_ON_ERROR);
        return $this->boolFromResponse($this->doPost('add-record', [], $postData));
    }
}

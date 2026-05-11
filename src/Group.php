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

// Group has the same definition as https://github.com/casdoor/casdoor/blob/master/object/group.go
class Group
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';
    public string $updatedTime = '';

    public string $displayName  = '';
    public string $manager      = '';
    public string $contactEmail = '';
    public string $type         = '';
    public string $parentId     = '';
    public bool   $isTopGroup   = false;
    public array  $users        = [];

    public string $title    = '';
    public string $key      = '';
    public array  $children = [];

    public bool $isEnabled = false;
}

trait GroupTrait
{
    public function getGroups(): array
    {
        $url = $this->getUrl('get-groups', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getPaginationGroups(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-groups', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getGroup(string $name): ?array
    {
        $url = $this->getUrl('get-group', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function addGroup(Group $group): bool
    {
        return $this->modifyGroup('add-group', $group);
    }

    public function updateGroup(Group $group): bool
    {
        return $this->modifyGroup('update-group', $group);
    }

    public function deleteGroup(Group $group): bool
    {
        return $this->modifyGroup('delete-group', $group);
    }

    private function modifyGroup(string $action, Group $group): bool
    {
        $group->owner = $this->organizationName;
        $queryMap     = ['id' => $group->owner . '/' . $group->name];
        $postData     = json_encode($group, JSON_THROW_ON_ERROR);
        $response     = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

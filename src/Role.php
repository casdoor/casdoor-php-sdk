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

// Role has the same definition as https://github.com/casdoor/casdoor/blob/master/object/role.go
class Role
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';
    public string $displayName = '';
    public string $description = '';

    public array $users   = [];
    public array $groups  = [];
    public array $roles   = [];
    public array $domains = [];
    public bool  $isEnabled = false;
}

trait RoleTrait
{
    public function getRoles(): array
    {
        $url = $this->getUrl('get-roles', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getPaginationRoles(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-roles', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getRole(string $name): ?array
    {
        $url = $this->getUrl('get-role', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function updateRole(Role $role): bool
    {
        return $this->modifyRole('update-role', $role, []);
    }

    public function updateRoleForColumns(Role $role, array $columns): bool
    {
        return $this->modifyRole('update-role', $role, $columns);
    }

    public function addRole(Role $role): bool
    {
        return $this->modifyRole('add-role', $role, []);
    }

    public function deleteRole(Role $role): bool
    {
        return $this->modifyRole('delete-role', $role, []);
    }

    private function modifyRole(string $action, Role $role, array $columns): bool
    {
        $role->owner  = $this->organizationName;
        $queryMap     = ['id' => $role->owner . '/' . $role->name];
        if (!empty($columns)) {
            $queryMap['columns'] = implode(',', $columns);
        }
        $postData = json_encode($role, JSON_THROW_ON_ERROR);
        $response = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

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

// Permission has the same definition as https://github.com/casdoor/casdoor/blob/master/object/permission.go
class Permission
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

    public string $model        = '';
    public string $adapter      = '';
    public string $resourceType = '';
    public array  $resources    = [];
    public array  $actions      = [];
    public string $effect       = '';
    public bool   $isEnabled    = false;

    public string $submitter   = '';
    public string $approver    = '';
    public string $approveTime = '';
    public string $state       = '';
}

trait PermissionTrait
{
    public function getPermissions(): array
    {
        $url = $this->getUrl('get-permissions', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getPermissionsByRole(string $name): array
    {
        $url = $this->getUrl('get-permissions-by-role', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function getPaginationPermissions(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-permissions', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getPermission(string $name): ?array
    {
        $url = $this->getUrl('get-permission', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function updatePermission(Permission $permission): bool
    {
        return $this->modifyPermission('update-permission', $permission, []);
    }

    public function updatePermissionForColumns(Permission $permission, array $columns): bool
    {
        return $this->modifyPermission('update-permission', $permission, $columns);
    }

    public function addPermission(Permission $permission): bool
    {
        return $this->modifyPermission('add-permission', $permission, []);
    }

    public function deletePermission(Permission $permission): bool
    {
        return $this->modifyPermission('delete-permission', $permission, []);
    }

    private function modifyPermission(string $action, Permission $permission, array $columns): bool
    {
        $permission->owner = $this->organizationName;
        $queryMap          = ['id' => $permission->owner . '/' . $permission->name];
        if (!empty($columns)) {
            $queryMap['columns'] = implode(',', $columns);
        }
        $postData = json_encode($permission, JSON_THROW_ON_ERROR);
        $response = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

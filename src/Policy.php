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

class CasbinRule
{
    public int    $id    = 0;
    public string $ptype = '';
    public string $v0    = '';
    public string $v1    = '';
    public string $v2    = '';
    public string $v3    = '';
    public string $v4    = '';
    public string $v5    = '';
}

class PolicyFilter
{
    public string  $ptype       = '';
    public ?int    $fieldIndex  = null;
    public array   $fieldValues = [];
}

trait PolicyTrait
{
    public function getPolicies(string $enforcerName, string $adapterId): array
    {
        $url = $this->getUrl('get-policies', [
            'id'        => $this->organizationName . '/' . $enforcerName,
            'adapterId' => $adapterId,
        ]);
        return $this->doGetBytes($url);
    }

    public function getFilteredPolicies(string $enforcerId, array $filters): array
    {
        $postData = json_encode($filters, JSON_THROW_ON_ERROR);
        $response = $this->doPost('get-filtered-policies', ['id' => $enforcerId], $postData);
        return $response['data'] ?? [];
    }

    public function addPolicy(Enforcer $enforcer, CasbinRule $policy): bool
    {
        return $this->modifyPolicy('add-policy', $enforcer, [$policy]);
    }

    public function updatePolicy(Enforcer $enforcer, CasbinRule $oldPolicy, CasbinRule $newPolicy): bool
    {
        return $this->modifyPolicy('update-policy', $enforcer, [$oldPolicy, $newPolicy]);
    }

    public function removePolicy(Enforcer $enforcer, CasbinRule $policy): bool
    {
        return $this->modifyPolicy('remove-policy', $enforcer, [$policy]);
    }

    private function modifyPolicy(string $action, Enforcer $enforcer, array $policies): bool
    {
        $enforcer->owner = $this->organizationName;
        $queryMap        = ['id' => $enforcer->owner . '/' . $enforcer->name];
        $postData        = $action === 'update-policy'
            ? json_encode($policies, JSON_THROW_ON_ERROR)
            : json_encode($policies[0], JSON_THROW_ON_ERROR);
        $response = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

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

trait EnforceTrait
{
    /**
     * Checks the request (e.g. ['alice', 'data1', 'read']) against a permission, a model, a resource, an enforcer
     * or all the permissions of an owner, it's allowed if any of the matched permissions allows it.
     */
    public function enforce(string $permissionId, string $modelId, string $resourceId, string $enforcerId, string $owner, array $casbinRequest): bool
    {
        $response = $this->doEnforce('enforce', $permissionId, $modelId, $resourceId, $enforcerId, $owner, $casbinRequest);
        foreach ((array) ($response['data'] ?? []) as $allowed) {
            if (!is_bool($allowed)) {
                throw new Exceptions\CasdoorException('invalid data');
            }
            if ($allowed) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks the requests in a batch, returns the results of each matched permission.
     *
     * @return bool[][]
     */
    public function batchEnforce(string $permissionId, string $modelId, string $resourceId, string $enforcerId, string $owner, array $casbinRequests): array
    {
        $response = $this->doEnforce('batch-enforce', $permissionId, $modelId, $resourceId, $enforcerId, $owner, $casbinRequests);
        return (array) ($response['data'] ?? []);
    }

    private function doEnforce(string $action, string $permissionId, string $modelId, string $resourceId, string $enforcerId, string $owner, array $request): array
    {
        $queryMap = [
            'permissionId' => $permissionId,
            'modelId'      => $modelId,
            'resourceId'   => $resourceId,
            'enforcerId'   => $enforcerId,
            'owner'        => $owner,
        ];
        return $this->doPost($action, $queryMap, json_encode($request, JSON_THROW_ON_ERROR));
    }
}

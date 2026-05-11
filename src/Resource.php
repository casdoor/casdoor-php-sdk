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

// Resource has the same definition as https://github.com/casdoor/casdoor/blob/master/object/resource.go
class Resource
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';

    public string $user        = '';
    public string $provider    = '';
    public string $application = '';
    public string $tag         = '';
    public string $parent      = '';
    public string $fileName    = '';
    public string $fileType    = '';
    public string $fileFormat  = '';
    public int    $fileSize    = 0;
    public string $url         = '';
    public string $description = '';
}

trait ResourceTrait
{
    public function getResource(string $id): ?array
    {
        $url = $this->getUrl('get-resource', ['owner' => $this->organizationName, 'id' => $id]);
        return $this->doGetBytes($url);
    }

    public function getResources(string $owner, string $user, string $field, string $value, string $sortField, string $sortOrder): array
    {
        $queryMap = compact('owner', 'user', 'field', 'value', 'sortField', 'sortOrder');
        $url      = $this->getUrl('get-resources', $queryMap);
        return $this->doGetBytes($url);
    }

    public function getPaginationResources(string $owner, string $user, string $field, string $value, int $page, int $pageSize, string $sortField, string $sortOrder): array
    {
        $queryMap = [
            'owner'     => $owner,
            'user'      => $user,
            'field'     => $field,
            'value'     => $value,
            'p'         => (string) $page,
            'pageSize'  => (string) $pageSize,
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ];
        $url = $this->getUrl('get-resources', $queryMap);
        return $this->doGetBytes($url);
    }

    public function uploadResource(string $user, string $tag, string $parent, string $fullFilePath, string $fileBytes): array
    {
        $queryMap = [
            'owner'        => $this->organizationName,
            'user'         => $user,
            'application'  => $this->applicationName,
            'tag'          => $tag,
            'parent'       => $parent,
            'fullFilePath' => $fullFilePath,
        ];
        $response = $this->doPost('upload-resource', $queryMap, $fileBytes, true, true);
        return [$response['data'], $response['data2']];
    }

    public function deleteResource(Resource $resource, string $tag = ''): bool
    {
        if ($resource->owner === '') {
            $resource->owner = $this->organizationName;
        }
        $queryMap = ['tag' => $tag];
        $postData = json_encode($resource, JSON_THROW_ON_ERROR);
        $response = $this->doPost('delete-resource', $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

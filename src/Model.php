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

// Model has the same definition as https://github.com/casdoor/casdoor/blob/master/object/model.go
class Model
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
    public bool   $isTopModel   = false;
    public array  $users        = [];

    public string $title    = '';
    public string $key      = '';
    public array  $children = [];

    public string $modelText = '';
    public bool   $isEnabled = false;
}

trait ModelTrait
{
    public function getModels(): array
    {
        $url = $this->getUrl('get-models', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getPaginationModels(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-models', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getModel(string $name): ?array
    {
        $url = $this->getUrl('get-model', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function addModel(Model $model): bool
    {
        return $this->modifyModel('add-model', $model);
    }

    public function updateModel(Model $model): bool
    {
        return $this->modifyModel('update-model', $model);
    }

    public function deleteModel(Model $model): bool
    {
        return $this->modifyModel('delete-model', $model);
    }

    private function modifyModel(string $action, Model $model): bool
    {
        $model->owner = $this->organizationName;
        $queryMap     = ['id' => $model->owner . '/' . $model->name];
        $postData     = json_encode($model, JSON_THROW_ON_ERROR);
        $response     = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

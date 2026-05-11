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

// Plan has the same definition as https://github.com/casdoor/casdoor/blob/master/object/plan.go
class Plan
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';
    public string $displayName = '';
    public string $description = '';

    public float  $price            = 0.0;
    public string $currency         = '';
    public string $period           = '';
    public string $product          = '';
    public array  $paymentProviders = [];
    public bool   $isEnabled        = false;

    public string $role    = '';
    public array  $options = [];
}

trait PlanTrait
{
    public function getPlans(): array
    {
        $url = $this->getUrl('get-plans', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getPaginationPlans(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-plans', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getPlan(string $name): ?array
    {
        $url = $this->getUrl('get-plan', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function addPlan(Plan $plan): bool
    {
        return $this->modifyPlan('add-plan', $plan);
    }

    public function updatePlan(Plan $plan): bool
    {
        return $this->modifyPlan('update-plan', $plan);
    }

    public function deletePlan(Plan $plan): bool
    {
        return $this->modifyPlan('delete-plan', $plan);
    }

    private function modifyPlan(string $action, Plan $plan): bool
    {
        $plan->owner = $this->organizationName;
        $queryMap    = ['id' => $plan->owner . '/' . $plan->name];
        $postData    = json_encode($plan, JSON_THROW_ON_ERROR);
        $response    = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

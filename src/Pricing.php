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

// Pricing has the same definition as https://github.com/casdoor/casdoor/blob/master/object/pricing.go
class Pricing
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';
    public string $displayName = '';
    public string $description = '';

    public array  $plans         = [];
    public bool   $isEnabled     = false;
    public int    $trialDuration = 0;
    public string $application   = '';

    public string $submitter   = '';
    public string $approver    = '';
    public string $approveTime = '';
    public string $state       = '';
}

trait PricingTrait
{
    public function getPricings(): array
    {
        $url = $this->getUrl('get-pricings', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getPaginationPricings(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-pricings', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getPricing(string $name): ?array
    {
        $url = $this->getUrl('get-pricing', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function addPricing(Pricing $pricing): bool
    {
        return $this->modifyPricing('add-pricing', $pricing);
    }

    public function updatePricing(Pricing $pricing): bool
    {
        return $this->modifyPricing('update-pricing', $pricing);
    }

    public function deletePricing(Pricing $pricing): bool
    {
        return $this->modifyPricing('delete-pricing', $pricing);
    }

    private function modifyPricing(string $action, Pricing $pricing): bool
    {
        $pricing->owner = $this->organizationName;
        $queryMap       = ['id' => $pricing->owner . '/' . $pricing->name];
        $postData       = json_encode($pricing, JSON_THROW_ON_ERROR);
        $response       = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

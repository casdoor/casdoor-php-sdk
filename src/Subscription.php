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

// Subscription has the same definition as https://github.com/casdoor/casdoor/blob/master/object/subscription.go
class Subscription
{
    public string $owner       = '';
    public string $name        = '';
    public string $displayName = '';
    public string $createdTime = '';
    public string $description = '';

    public string $user    = '';
    public string $pricing = '';
    public string $plan    = '';
    public string $payment = '';

    public string $startTime = '';
    public string $endTime   = '';
    public string $period    = '';
    public string $state     = '';
}

trait SubscriptionTrait
{
    public function getSubscriptions(): array
    {
        $url = $this->getUrl('get-subscriptions', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getPaginationSubscriptions(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-subscriptions', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getSubscription(string $name): ?array
    {
        $url = $this->getUrl('get-subscription', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function addSubscription(Subscription $subscription): bool
    {
        return $this->modifySubscription('add-subscription', $subscription);
    }

    public function updateSubscription(Subscription $subscription): bool
    {
        return $this->modifySubscription('update-subscription', $subscription);
    }

    public function deleteSubscription(Subscription $subscription): bool
    {
        return $this->modifySubscription('delete-subscription', $subscription);
    }

    private function modifySubscription(string $action, Subscription $subscription): bool
    {
        $subscription->owner = $this->organizationName;
        $queryMap            = ['id' => $subscription->owner . '/' . $subscription->name];
        $postData            = json_encode($subscription, JSON_THROW_ON_ERROR);
        $response            = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

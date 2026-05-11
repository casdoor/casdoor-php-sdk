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

// Order has the same definition as https://github.com/casdoor/casdoor/blob/master/object/order.go
class Order
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';
    public string $updateTime  = '';
    public string $displayName = '';

    public array  $products     = [];
    public array  $productInfos = [];

    public string $user = '';

    public string $payment  = '';
    public float  $price    = 0.0;
    public string $currency = '';

    public string $state   = '';
    public string $message = '';

    public function getId(): string
    {
        return $this->owner . '/' . $this->name;
    }
}

trait OrderTrait
{
    public function getOrders(): array
    {
        $url = $this->getUrl('get-orders', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getPaginationOrders(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-orders', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getUserOrders(string $userName): array
    {
        $url = $this->getUrl('get-user-orders', ['owner' => $this->organizationName, 'user' => $userName]);
        return $this->doGetBytes($url);
    }

    public function getOrder(string $name): ?array
    {
        $url = $this->getUrl('get-order', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function addOrder(Order $order): bool
    {
        return $this->modifyOrder('add-order', $order);
    }

    public function updateOrder(Order $order): bool
    {
        return $this->modifyOrder('update-order', $order);
    }

    public function deleteOrder(Order $order): bool
    {
        return $this->modifyOrder('delete-order', $order);
    }

    public function cancelOrder(string $name): bool
    {
        $queryMap = ['id' => $this->organizationName . '/' . $name];
        $response = $this->doPost('cancel-order', $queryMap, '');
        return $this->boolFromResponse($response);
    }

    private function modifyOrder(string $action, Order $order): bool
    {
        $order->owner = $this->organizationName;
        $queryMap     = ['id' => $order->owner . '/' . $order->name];
        $postData     = json_encode($order, JSON_THROW_ON_ERROR);
        $response     = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

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

// Product has the same definition as https://github.com/casdoor/casdoor/blob/master/object/product.go
class Product
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';
    public string $displayName = '';

    public string $image                 = '';
    public string $detail                = '';
    public string $description           = '';
    public string $tag                   = '';
    public string $currency              = '';
    public float  $price                 = 0.0;
    public int    $quantity              = 0;
    public int    $sold                  = 0;
    public bool   $isRecharge            = false;
    public array  $rechargeOptions       = [];
    public bool   $disableCustomRecharge = false;
    public array  $providers             = [];
    public string $successUrl            = '';

    public string $state = '';

    public array $providerObjs = [];
}

trait ProductTrait
{
    public function getProducts(): array
    {
        $url = $this->getUrl('get-products', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getPaginationProducts(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-products', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getProduct(string $name): ?array
    {
        $url = $this->getUrl('get-product', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function addProduct(Product $product): bool
    {
        return $this->modifyProduct('add-product', $product);
    }

    public function updateProduct(Product $product): bool
    {
        return $this->modifyProduct('update-product', $product);
    }

    public function deleteProduct(Product $product): bool
    {
        return $this->modifyProduct('delete-product', $product);
    }

    private function modifyProduct(string $action, Product $product): bool
    {
        $product->owner = $this->organizationName;
        $queryMap       = ['id' => $product->owner . '/' . $product->name];
        $postData       = json_encode($product, JSON_THROW_ON_ERROR);
        $response       = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

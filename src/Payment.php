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

// Payment has the same definition as https://github.com/casdoor/casdoor/blob/master/object/payment.go
class Payment
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';
    public string $displayName = '';

    public string $provider = '';
    public string $type     = '';

    public array  $products            = [];
    public string $productsDisplayName = '';
    public string $detail              = '';
    public string $currency            = '';
    public float  $price               = 0.0;

    public string $user         = '';
    public string $personName   = '';
    public string $personIdCard = '';
    public string $personEmail  = '';
    public string $personPhone  = '';

    public string $invoiceType   = '';
    public string $invoiceTitle  = '';
    public string $invoiceTaxId  = '';
    public string $invoiceRemark = '';
    public string $invoiceUrl    = '';

    public string  $order      = '';
    public ?array  $orderObj   = null;
    public string  $outOrderId = '';
    public string  $payUrl     = '';
    public string  $successUrl = '';
    public string  $state      = '';
    public string  $message    = '';
}

trait PaymentTrait
{
    public function getPayments(): array
    {
        $url = $this->getUrl('get-payments', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getPaginationPayments(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-payments', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getPayment(string $name): ?array
    {
        $url = $this->getUrl('get-payment', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function getUserPayments(string $userName): array
    {
        $url = $this->getUrl('get-user-payments', [
            'owner'        => $this->organizationName,
            'organization' => $this->organizationName,
            'user'         => $userName,
        ]);
        return $this->doGetBytes($url);
    }

    public function addPayment(Payment $payment): bool
    {
        return $this->modifyPayment('add-payment', $payment);
    }

    public function updatePayment(Payment $payment): bool
    {
        return $this->modifyPayment('update-payment', $payment);
    }

    public function deletePayment(Payment $payment): bool
    {
        return $this->modifyPayment('delete-payment', $payment);
    }

    public function notifyPayment(Payment $payment): bool
    {
        return $this->modifyPayment('notify-payment', $payment);
    }

    public function invoicePayment(Payment $payment): bool
    {
        return $this->modifyPayment('invoice-payment', $payment);
    }

    private function modifyPayment(string $action, Payment $payment): bool
    {
        $payment->owner = $this->organizationName;
        $queryMap       = ['id' => $payment->owner . '/' . $payment->name];
        $postData       = json_encode($payment, JSON_THROW_ON_ERROR);
        $response       = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

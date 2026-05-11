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

// Transaction has the same definition as https://github.com/casdoor/casdoor/blob/master/object/transaction.go
class Transaction
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';
    public string $displayName = '';

    public string $application = '';
    public string $domain      = '';
    public string $category    = '';
    public string $type        = '';
    public string $subtype     = '';
    public string $provider    = '';
    public string $user        = '';
    public string $tag         = '';

    public float  $amount   = 0.0;
    public string $currency = '';

    public string $payment = '';

    public string $state = '';
}

trait TransactionTrait
{
    public function getTransactions(): array
    {
        $url = $this->getUrl('get-transactions', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getPaginationTransactions(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-transactions', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getTransaction(string $name): ?array
    {
        $url = $this->getUrl('get-transaction', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function getUserTransactions(string $userName): array
    {
        $url = $this->getUrl('get-user-transactions', ['owner' => $this->organizationName, 'user' => $userName]);
        return $this->doGetBytes($url);
    }

    public function addTransaction(Transaction $transaction): array
    {
        return $this->modifyTransactionWithDryRun('add-transaction', $transaction, [], false);
    }

    public function addTransactionWithDryRun(Transaction $transaction, bool $dryRun): array
    {
        return $this->modifyTransactionWithDryRun('add-transaction', $transaction, [], $dryRun);
    }

    public function updateTransaction(Transaction $transaction): bool
    {
        $transaction->owner = $this->organizationName;
        $queryMap           = ['id' => $transaction->owner . '/' . $transaction->name];
        $postData           = json_encode($transaction, JSON_THROW_ON_ERROR);
        $response           = $this->doPost('update-transaction', $queryMap, $postData);
        return $this->boolFromResponse($response);
    }

    public function deleteTransaction(Transaction $transaction): bool
    {
        $transaction->owner = $this->organizationName;
        $queryMap           = ['id' => $transaction->owner . '/' . $transaction->name];
        $postData           = json_encode($transaction, JSON_THROW_ON_ERROR);
        $response           = $this->doPost('delete-transaction', $queryMap, $postData);
        return $this->boolFromResponse($response);
    }

    private function modifyTransactionWithDryRun(string $action, Transaction $transaction, array $columns, bool $dryRun): array
    {
        $transaction->owner = $this->organizationName;
        $queryMap           = ['id' => $transaction->owner . '/' . $transaction->name];
        if (!empty($columns)) {
            $queryMap['columns'] = implode(',', $columns);
        }
        if ($dryRun && $action === 'add-transaction') {
            $queryMap['dryRun'] = '1';
        }
        $postData = json_encode($transaction, JSON_THROW_ON_ERROR);
        $response = $this->doPost($action, $queryMap, $postData);
        return [$this->boolFromResponse($response), $response['data'] ?? ''];
    }
}

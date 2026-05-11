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

// Webhook has the same definition as https://github.com/casdoor/casdoor/blob/master/object/webhook.go
class Webhook
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';

    public string $organization = '';

    public string $url            = '';
    public string $method         = '';
    public string $contentType    = '';
    public array  $headers        = [];
    public array  $events         = [];
    public array  $tokenFields    = [];
    public array  $objectFields   = [];
    public bool   $isUserExtended = false;
    public bool   $singleOrgOnly  = false;
    public bool   $isEnabled      = false;
}

trait WebhookTrait
{
    public function getWebhooks(): array
    {
        $url = $this->getUrl('get-webhooks', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getPaginationWebhooks(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-webhooks', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getWebhook(string $name): ?array
    {
        $url = $this->getUrl('get-webhook', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function addWebhook(Webhook $webhook): bool
    {
        return $this->modifyWebhook('add-webhook', $webhook);
    }

    public function updateWebhook(Webhook $webhook): bool
    {
        return $this->modifyWebhook('update-webhook', $webhook);
    }

    public function deleteWebhook(Webhook $webhook): bool
    {
        return $this->modifyWebhook('delete-webhook', $webhook);
    }

    private function modifyWebhook(string $action, Webhook $webhook): bool
    {
        $webhook->owner = $this->organizationName;
        $queryMap       = ['id' => $webhook->owner . '/' . $webhook->name];
        $postData       = json_encode($webhook, JSON_THROW_ON_ERROR);
        $response       = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

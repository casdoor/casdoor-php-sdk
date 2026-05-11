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

// Invitation has the same definition as https://github.com/casdoor/casdoor/blob/master/object/invitation.go
class Invitation
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';
    public string $updatedTime = '';
    public string $displayName = '';

    public string $code      = '';
    public bool   $isRegexp  = false;
    public int    $quota     = 0;
    public int    $usedCount = 0;

    public string $application  = '';
    public string $username     = '';
    public string $email        = '';
    public string $phone        = '';

    public string $signupGroup = '';
    public string $defaultCode = '';

    public string $state = '';

    public function getId(): string
    {
        return $this->owner . '/' . $this->name;
    }
}

trait InvitationTrait
{
    public function getInvitations(): array
    {
        $url = $this->getUrl('get-invitations', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getPaginationInvitations(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-invitations', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getInvitation(string $name): ?array
    {
        $url = $this->getUrl('get-invitation', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function getInvitationInfo(string $code, string $applicationName): ?array
    {
        $url = $this->getUrl('get-invitation-info', [
            'applicationId' => 'admin/' . $applicationName,
            'code'          => $code,
        ]);
        return $this->doGetBytes($url);
    }

    public function addInvitation(Invitation $invitation): bool
    {
        return $this->modifyInvitation('add-invitation', $invitation, []);
    }

    public function updateInvitation(Invitation $invitation): bool
    {
        return $this->modifyInvitation('update-invitation', $invitation, []);
    }

    public function updateInvitationForColumns(Invitation $invitation, array $columns): bool
    {
        return $this->modifyInvitation('update-invitation', $invitation, $columns);
    }

    public function deleteInvitation(Invitation $invitation): bool
    {
        return $this->modifyInvitation('delete-invitation', $invitation, []);
    }

    private function modifyInvitation(string $action, Invitation $invitation, array $columns): bool
    {
        if ($invitation->owner === '') {
            $invitation->owner = $this->organizationName;
        }
        $queryMap = ['id' => $invitation->owner . '/' . $invitation->name];
        if (!empty($columns)) {
            $queryMap['columns'] = implode(',', $columns);
        }
        $postData = json_encode($invitation, JSON_THROW_ON_ERROR);
        $response = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

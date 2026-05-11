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

// Organization has the same definition as https://github.com/casdoor/casdoor/blob/master/object/organization.go
class Organization
{
    public string  $owner       = '';
    public string  $name        = '';
    public string  $createdTime = '';

    public string $displayName            = '';
    public string $websiteUrl             = '';
    public string $logo                   = '';
    public string $logoDark               = '';
    public string $favicon                = '';
    public bool   $hasPrivilegeConsent    = false;
    public string $passwordType           = '';
    public string $passwordSalt           = '';
    public array  $passwordOptions        = [];
    public string $passwordObfuscatorType = '';
    public string $passwordObfuscatorKey  = '';
    public int    $passwordExpireDays     = 0;
    public array  $countryCodes           = [];
    public string $defaultAvatar          = '';
    public string $defaultApplication     = '';
    public array  $userTypes              = [];
    public array  $tags                   = [];
    public array  $languages              = [];
    public ?array $themeData              = null;
    public string $masterPassword         = '';
    public string $defaultPassword        = '';
    public string $masterVerificationCode = '';
    public string $ipWhitelist            = '';
    public int    $initScore              = 0;
    public bool   $enableSoftDeletion     = false;
    public bool   $isProfilePublic        = false;
    public bool   $useEmailAsUsername     = false;
    public bool   $enableTour             = false;
    public bool   $disableSignin          = false;
    public string $ipRestriction          = '';
    public array  $navItems               = [];
    public array  $userNavItems           = [];
    public array  $widgetItems            = [];

    public array  $mfaItems           = [];
    public int    $mfaRememberInHours  = 0;
    public array  $accountItems        = [];

    public float  $orgBalance      = 0.0;
    public float  $userBalance     = 0.0;
    public float  $balanceCredit   = 0.0;
    public string $balanceCurrency = '';
}

trait OrganizationTrait
{
    public function getOrganization(string $name): ?array
    {
        $url = $this->getUrl('get-organization', ['id' => 'admin/' . $name]);
        return $this->doGetBytes($url);
    }

    public function getOrganizations(): array
    {
        $url = $this->getUrl('get-organizations', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getOrganizationNames(): array
    {
        $url = $this->getUrl('get-organization-names', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function addOrganization(Organization $organization): bool
    {
        return $this->modifyOrganization('add-organization', $organization);
    }

    public function updateOrganization(Organization $organization): bool
    {
        return $this->modifyOrganization('update-organization', $organization);
    }

    public function deleteOrganization(Organization $organization): bool
    {
        return $this->modifyOrganization('delete-organization', $organization);
    }

    private function modifyOrganization(string $action, Organization $organization): bool
    {
        if ($organization->owner === '') {
            $organization->owner = 'admin';
        }
        $queryMap = ['id' => $organization->owner . '/' . $organization->name];
        $postData = json_encode($organization, JSON_THROW_ON_ERROR);
        $response = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

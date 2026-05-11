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

// Ldap has the same definition as https://github.com/casdoor/casdoor/blob/master/object/ldap.go
class Ldap
{
    public string $id          = '';
    public string $owner       = '';
    public string $createdTime = '';

    public string $serverName          = '';
    public string $host                = '';
    public int    $port                = 0;
    public bool   $enableSsl           = false;
    public bool   $allowSelfSignedCert = false;
    public string $username            = '';
    public string $password            = '';
    public string $baseDn              = '';
    public string $filter              = '';
    public array  $filterFields        = [];
    public string $defaultGroup        = '';
    public string $passwordType        = '';
    public array  $customAttributes    = [];

    public int    $autoSync = 0;
    public string $lastSync = '';
}

class LdapUser
{
    public string $uidNumber             = '';
    public string $uid                   = '';
    public string $cn                    = '';
    public string $gidNumber             = '';
    public string $uuid                  = '';
    public string $userPrincipalName     = '';
    public string $displayName           = '';
    public string $mail                  = '';
    public string $email                 = '';
    public string $emailAddress          = '';
    public string $telephoneNumber       = '';
    public string $mobile                = '';
    public string $mobileTelephoneNumber = '';
    public string $registeredAddress     = '';
    public string $postalAddress         = '';
    public string $country               = '';
    public string $countryName           = '';
    public string $groupId               = '';
    public string $address               = '';
    public string $memberOf              = '';
    public array  $attributes            = [];
}

trait LdapTrait
{
    public function getLdaps(): array
    {
        $url = $this->getUrl('get-ldaps', ['owner' => 'admin']);
        return $this->doGetBytes($url);
    }

    public function getLdap(string $id): ?array
    {
        $url = $this->getUrl('get-ldap', ['id' => 'admin/' . $id]);
        return $this->doGetBytes($url);
    }

    public function addLdap(Ldap $ldap): bool
    {
        return $this->modifyLdap('add-ldap', $ldap);
    }

    public function updateLdap(Ldap $ldap): bool
    {
        return $this->modifyLdap('update-ldap', $ldap);
    }

    public function deleteLdap(Ldap $ldap): bool
    {
        return $this->modifyLdap('delete-ldap', $ldap);
    }

    public function getLdapUsers(string $id): array
    {
        $url = $this->getUrl('get-ldap-users', ['id' => $this->organizationName . '/' . $id]);
        return $this->doGetBytes($url);
    }

    public function syncLdapUsers(string $id, array $users): array
    {
        $queryMap = ['id' => $this->organizationName . '/' . $id];
        $postData = json_encode($users, JSON_THROW_ON_ERROR);
        $response = $this->doPost('sync-ldap-users', $queryMap, $postData);
        return $response['data'] ?? [];
    }

    public function syncLdapUsersFromServer(string $id): array
    {
        $ldapUsersResp = $this->getLdapUsers($id);
        $users         = $ldapUsersResp['users'] ?? [];
        return $this->syncLdapUsers($id, $users);
    }

    private function modifyLdap(string $action, Ldap $ldap): bool
    {
        if ($ldap->owner === '') {
            $ldap->owner = 'admin';
        }
        $queryMap = ['id' => $ldap->owner . '/' . $ldap->id];
        $postData = json_encode($ldap, JSON_THROW_ON_ERROR);
        $response = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

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

// User has the same definition as https://github.com/casdoor/casdoor/blob/master/object/user.go
class User
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';
    public string $updatedTime = '';
    public string $deletedTime = '';

    public string $id                   = '';
    public string $externalId           = '';
    public string $type                 = '';
    public string $password             = '';
    public string $passwordSalt         = '';
    public string $passwordType         = '';
    public string $displayName          = '';
    public string $firstName            = '';
    public string $lastName             = '';
    public string $avatar               = '';
    public string $avatarType           = '';
    public string $permanentAvatar      = '';
    public string $email                = '';
    public bool   $emailVerified        = false;
    public string $phone                = '';
    public string $countryCode          = '';
    public string $region               = '';
    public string $location             = '';
    public array  $address              = [];
    public array  $addresses            = [];
    public string $affiliation          = '';
    public string $title                = '';
    public string $idCardType           = '';
    public string $idCard               = '';
    public string $realName             = '';
    public bool   $isVerified           = false;
    public string $homepage             = '';
    public string $bio                  = '';
    public string $tag                  = '';
    public string $language             = '';
    public string $gender               = '';
    public string $birthday             = '';
    public string $education            = '';
    public int    $score                = 0;
    public int    $karma                = 0;
    public int    $ranking              = 0;
    public float  $balance              = 0.0;
    public float  $balanceCredit        = 0.0;
    public string $currency             = '';
    public string $balanceCurrency      = '';
    public bool   $isDefaultAvatar      = false;
    public bool   $isOnline             = false;
    public bool   $isAdmin              = false;
    public bool   $isForbidden          = false;
    public bool   $isDeleted            = false;
    public string $signupApplication    = '';
    public string $hash                 = '';
    public string $preHash              = '';
    public string $registerType         = '';
    public string $registerSource       = '';
    public string $accessKey            = '';
    public string $accessSecret         = '';
    public string $accessToken          = '';
    public string $originalToken        = '';
    public string $originalRefreshToken = '';

    public string $createdIp      = '';
    public string $lastSigninTime = '';
    public string $lastSigninIp   = '';

    public string $github          = '';
    public string $google          = '';
    public string $qq              = '';
    public string $wechat          = '';
    public string $facebook        = '';
    public string $dingtalk        = '';
    public string $weibo           = '';
    public string $gitee           = '';
    public string $linkedin        = '';
    public string $wecom           = '';
    public string $lark            = '';
    public string $gitlab          = '';
    public string $adfs            = '';
    public string $baidu           = '';
    public string $alipay          = '';
    public string $casdoor         = '';
    public string $infoflow        = '';
    public string $apple           = '';
    public string $azureAD         = '';
    public string $azureADB2c      = '';
    public string $slack           = '';
    public string $steam           = '';
    public string $bilibili        = '';
    public string $okta            = '';
    public string $douyin          = '';
    public string $kwai            = '';
    public string $line            = '';
    public string $amazon          = '';
    public string $auth0           = '';
    public string $battleNet       = '';
    public string $bitbucket       = '';
    public string $box             = '';
    public string $cloudFoundry    = '';
    public string $dailymotion     = '';
    public string $deezer          = '';
    public string $digitalOcean    = '';
    public string $discord         = '';
    public string $dropbox         = '';
    public string $eveOnline       = '';
    public string $fitbit          = '';
    public string $gitea           = '';
    public string $heroku          = '';
    public string $influxCloud     = '';
    public string $instagram       = '';
    public string $intercom        = '';
    public string $kakao           = '';
    public string $lastfm          = '';
    public string $mailru          = '';
    public string $meetup          = '';
    public string $microsoftOnline = '';
    public string $naver           = '';
    public string $nextcloud       = '';
    public string $oneDrive        = '';
    public string $oura            = '';
    public string $patreon         = '';
    public string $paypal          = '';
    public string $salesForce      = '';
    public string $shopify         = '';
    public string $soundcloud      = '';
    public string $spotify         = '';
    public string $strava          = '';
    public string $stripe          = '';
    public string $tikTok          = '';
    public string $tumblr          = '';
    public string $twitch          = '';
    public string $twitter         = '';
    public string $typetalk        = '';
    public string $uber            = '';
    public string $vk              = '';
    public string $wepay           = '';
    public string $xero            = '';
    public string $yahoo           = '';
    public string $yammer          = '';
    public string $yandex          = '';
    public string $zoom            = '';
    public string $metaMask        = '';
    public string $web3Onboard     = '';
    public string $custom          = '';
    public string $custom2         = '';
    public string $custom3         = '';
    public string $custom4         = '';
    public string $custom5         = '';
    public string $custom6         = '';
    public string $custom7         = '';
    public string $custom8         = '';
    public string $custom9         = '';
    public string $custom10        = '';

    public string $preferredMfaType    = '';
    public array  $recoveryCodes       = [];
    public string $totpSecret          = '';
    public bool   $mfaPhoneEnabled     = false;
    public bool   $mfaEmailEnabled     = false;
    public bool   $mfaRadiusEnabled    = false;
    public string $mfaRadiusUsername   = '';
    public string $mfaRadiusProvider   = '';
    public bool   $mfaPushEnabled      = false;
    public string $mfaPushReceiver     = '';
    public string $mfaPushProvider     = '';
    public array  $multiFactorAuths    = [];
    public string $invitation          = '';
    public string $invitationCode      = '';
    public array  $faceIds             = [];
    public array  $cart                = [];

    public string $ldap       = '';
    public array  $properties = [];

    public array  $roles       = [];
    public array  $permissions = [];
    public array  $groups      = [];

    public string $lastChangePasswordTime = '';
    public string $lastSigninWrongTime    = '';
    public int    $signinWrongTimes       = 0;

    public array  $managedAccounts     = [];
    public array  $mfaAccounts         = [];
    public array  $mfaItems            = [];
    public string $mfaRememberDeadline = '';
    public bool   $needUpdatePassword  = false;
    public string $ipWhitelist         = '';

    public function getId(): string
    {
        return $this->owner . '/' . $this->name;
    }
}

trait UserTrait
{
    public function getGlobalUsers(): array
    {
        $url = $this->getUrl('get-global-users');
        return $this->doGetBytes($url);
    }

    public function getUsers(): array
    {
        $queryMap = ['owner' => $this->organizationName];
        $url      = $this->getUrl('get-users', $queryMap);
        return $this->doGetBytes($url);
    }

    public function getPaginationUsers(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-users', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getSortedUsers(string $sorter, int $limit): array
    {
        $queryMap = [
            'owner'  => $this->organizationName,
            'sorter' => $sorter,
            'limit'  => (string) $limit,
        ];
        $url = $this->getUrl('get-sorted-users', $queryMap);
        return $this->doGetBytes($url);
    }

    public function getUserCount(string $isOnline): int
    {
        $queryMap = [
            'owner'    => $this->organizationName,
            'isOnline' => $isOnline,
        ];
        $url = $this->getUrl('get-user-count', $queryMap);
        return (int) $this->doGetBytes($url);
    }

    public function getUser(string $name): ?array
    {
        $queryMap = ['id' => $this->organizationName . '/' . $name];
        $url      = $this->getUrl('get-user', $queryMap);
        return $this->doGetBytes($url);
    }

    public function getUserByEmail(string $email): ?array
    {
        $queryMap = ['owner' => $this->organizationName, 'email' => $email];
        $url      = $this->getUrl('get-user', $queryMap);
        return $this->doGetBytes($url);
    }

    public function getUserByPhone(string $phone): ?array
    {
        $queryMap = ['owner' => $this->organizationName, 'phone' => $phone];
        $url      = $this->getUrl('get-user', $queryMap);
        return $this->doGetBytes($url);
    }

    public function getUserByUserId(string $userId): ?array
    {
        $queryMap = ['owner' => $this->organizationName, 'userId' => $userId];
        $url      = $this->getUrl('get-user', $queryMap);
        return $this->doGetBytes($url);
    }

    public function setPassword(string $owner, string $name, string $oldPassword, string $newPassword): bool
    {
        $param     = compact('owner', 'name', 'oldPassword', 'newPassword');
        $postBytes = json_encode([
            'userOwner'   => $owner,
            'userName'    => $name,
            'oldPassword' => $oldPassword,
            'newPassword' => $newPassword,
        ], JSON_THROW_ON_ERROR);
        $response = $this->doPost('set-password', [], $postBytes, true, false);
        return ($response['status'] ?? '') === 'ok';
    }

    public function updateUser(User $user): bool
    {
        return $this->modifyUser('update-user', $user, []);
    }

    public function updateUserForColumns(User $user, array $columns): bool
    {
        return $this->modifyUser('update-user', $user, $columns);
    }

    public function addUser(User $user): bool
    {
        return $this->modifyUser('add-user', $user, []);
    }

    public function deleteUser(User $user): bool
    {
        return $this->modifyUser('delete-user', $user, []);
    }

    public function checkUserPassword(User $user): bool
    {
        $queryMap = ['id' => $user->getId()];
        if ($user->owner === '') {
            $user->owner = $this->organizationName;
        }
        $postData = json_encode($user, JSON_THROW_ON_ERROR);
        $response = $this->doPost('check-user-password', $queryMap, $postData);
        return ($response['status'] ?? '') === 'ok';
    }

    private function modifyUser(string $action, User $user, array $columns): bool
    {
        if ($user->owner === '') {
            $user->owner = $this->organizationName;
        }
        $queryMap = ['id' => $user->getId()];
        if (!empty($columns)) {
            $queryMap['columns'] = implode(',', $columns);
        }
        $postData = json_encode($user, JSON_THROW_ON_ERROR);
        $response = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

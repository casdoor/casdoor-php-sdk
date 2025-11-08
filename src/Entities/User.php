<?php

// Copyright 2023 The Casdoor Authors. All Rights Reserved.
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

namespace Casdoor\Entities;

/**
 * User represents a Casdoor user
 * This has the same definition as https://github.com/casdoor/casdoor/blob/master/object/user.go
 */
class User
{
    public string $owner = '';
    public string $name = '';
    public string $createdTime = '';
    public string $updatedTime = '';
    
    public string $id = '';
    public string $externalId = '';
    public string $type = '';
    public string $password = '';
    public string $passwordSalt = '';
    public string $passwordType = '';
    public string $displayName = '';
    public string $firstName = '';
    public string $lastName = '';
    public string $avatar = '';
    public string $avatarType = '';
    public string $permanentAvatar = '';
    public string $email = '';
    public bool $emailVerified = false;
    public string $phone = '';
    public string $countryCode = '';
    public string $region = '';
    public string $location = '';
    public array $address = [];
    public string $affiliation = '';
    public string $title = '';
    public string $idCardType = '';
    public string $idCard = '';
    public string $homepage = '';
    public string $bio = '';
    public string $tag = '';
    public string $language = '';
    public string $gender = '';
    public string $birthday = '';
    public string $education = '';
    public int $score = 0;
    public int $karma = 0;
    public int $ranking = 0;
    public bool $isDefaultAvatar = false;
    public bool $isOnline = false;
    public bool $isAdmin = false;
    public bool $isForbidden = false;
    public bool $isDeleted = false;
    public string $signupApplication = '';
    public string $hash = '';
    public string $preHash = '';
    public string $accessKey = '';
    public string $accessSecret = '';
    
    public string $createdIp = '';
    public string $lastSigninTime = '';
    public string $lastSigninIp = '';
    
    // OAuth providers
    public string $github = '';
    public string $google = '';
    public string $qq = '';
    public string $wechat = '';
    public string $facebook = '';
    public string $dingtalk = '';
    public string $weibo = '';
    public string $gitee = '';
    public string $linkedin = '';
    public string $wecom = '';
    public string $lark = '';
    public string $gitlab = '';
    public string $adfs = '';
    public string $baidu = '';
    public string $alipay = '';
    public string $casdoor = '';
    public string $infoflow = '';
    public string $apple = '';
    public string $azuread = '';
    public string $slack = '';
    public string $steam = '';
    public string $bilibili = '';
    public string $okta = '';
    public string $douyin = '';
    public string $line = '';
    public string $amazon = '';
    public string $auth0 = '';
    public string $battlenet = '';
    public string $bitbucket = '';
    public string $box = '';
    public string $cloudfoundry = '';
    public string $dailymotion = '';
    public string $deezer = '';
    public string $digitalocean = '';
    public string $discord = '';
    public string $dropbox = '';
    public string $eveonline = '';
    public string $fitbit = '';
    public string $gitea = '';
    public string $heroku = '';
    public string $influxcloud = '';
    public string $instagram = '';
    public string $intercom = '';
    public string $kakao = '';
    public string $lastfm = '';
    public string $mailru = '';
    public string $meetup = '';
    public string $microsoftonline = '';
    public string $naver = '';
    public string $nextcloud = '';
    public string $onedrive = '';
    public string $oura = '';
    public string $patreon = '';
    public string $paypal = '';
    public string $salesforce = '';
    public string $shopify = '';
    public string $soundcloud = '';
    public string $spotify = '';
    public string $strava = '';
    public string $stripe = '';
    public string $tiktok = '';
    public string $tumblr = '';
    public string $twitch = '';
    public string $twitter = '';
    public string $typetalk = '';
    public string $uber = '';
    public string $vk = '';
    public string $wepay = '';
    public string $xero = '';
    public string $yahoo = '';
    public string $yammer = '';
    public string $yandex = '';
    public string $zoom = '';
    public string $metamask = '';
    public string $web3onboard = '';
    public string $custom = '';
    
    // MFA fields
    public string $preferredMfaType = '';
    public array $recoveryCodes = [];
    public string $totpSecret = '';
    public bool $mfaPhoneEnabled = false;
    public bool $mfaEmailEnabled = false;
    
    public string $invitation = '';
    public string $invitationCode = '';
    
    public string $ldap = '';
    public array $properties = [];
    
    public array $roles = [];
    public array $permissions = [];
    public array $groups = [];
    public string $lastChangePasswordTime = '';
    
    public string $lastSigninWrongTime = '';
    public int $signinWrongTimes = 0;
    
    public array $managedAccounts = [];
    public bool $needUpdatePassword = false;

    /**
     * Get the full ID in format "owner/name"
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->owner . '/' . $this->name;
    }

    /**
     * Convert to array for JSON serialization
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * Create User from array
     *
     * @param array<string, mixed> $data
     * @return User
     */
    public static function fromArray(array $data): User
    {
        $user = new self();
        
        foreach ($data as $key => $value) {
            if (property_exists($user, $key)) {
                $user->$key = $value;
            }
        }
        
        return $user;
    }
}

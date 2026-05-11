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

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;

trait AuthTrait
{
    public function getSignupUrl(bool $enablePassword, string $redirectUri = ''): string
    {
        if ($enablePassword) {
            return sprintf('%s/signup/%s', $this->endpoint, $this->applicationName);
        }
        return str_replace('/login/oauth/authorize', '/signup/oauth/authorize', $this->getSigninUrl($redirectUri));
    }

    public function getSigninUrl(string $redirectUri): string
    {
        return sprintf(
            '%s/login/oauth/authorize?client_id=%s&response_type=code&redirect_uri=%s&scope=read&state=%s',
            $this->endpoint,
            $this->clientId,
            urlencode($redirectUri),
            $this->applicationName
        );
    }

    public function getUserProfileUrl(string $userName, string $accessToken = ''): string
    {
        $param = $accessToken !== '' ? '?access_token=' . $accessToken : '';
        return sprintf('%s/users/%s/%s%s', $this->endpoint, $this->organizationName, $userName, $param);
    }

    public function getMyProfileUrl(string $accessToken = ''): string
    {
        $param = $accessToken !== '' ? '?access_token=' . $accessToken : '';
        return sprintf('%s/account%s', $this->endpoint, $param);
    }

    public function getOAuthToken(string $code, string $state): AccessToken
    {
        $provider = new GenericProvider([
            'clientId'                => $this->clientId,
            'clientSecret'            => $this->clientSecret,
            'redirectUri'             => '',
            'urlAuthorize'            => sprintf('%s/api/login/oauth/authorize', $this->endpoint),
            'urlAccessToken'          => sprintf('%s/api/login/oauth/access_token', $this->endpoint),
            'urlResourceOwnerDetails' => '',
        ]);

        return $provider->getAccessToken('authorization_code', ['code' => $code]);
    }
}

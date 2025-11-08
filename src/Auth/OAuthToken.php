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

namespace Casdoor\Auth;

/**
 * OAuthToken represents an OAuth 2.0 access token
 */
class OAuthToken
{
    /**
     * @var string The access token
     */
    public string $accessToken;

    /**
     * @var string The token type (usually "Bearer")
     */
    public string $tokenType;

    /**
     * @var int Number of seconds until the token expires
     */
    public int $expiresIn;

    /**
     * @var string The refresh token
     */
    public string $refreshToken;

    /**
     * @var string The scope of the token
     */
    public string $scope;

    /**
     * Create a new OAuthToken instance
     *
     * @param string $accessToken
     * @param string $tokenType
     * @param int $expiresIn
     * @param string $refreshToken
     * @param string $scope
     */
    public function __construct(
        string $accessToken,
        string $tokenType = 'Bearer',
        int $expiresIn = 0,
        string $refreshToken = '',
        string $scope = ''
    ) {
        $this->accessToken = $accessToken;
        $this->tokenType = $tokenType;
        $this->expiresIn = $expiresIn;
        $this->refreshToken = $refreshToken;
        $this->scope = $scope;
    }

    /**
     * Get the access token string
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->accessToken;
    }
}

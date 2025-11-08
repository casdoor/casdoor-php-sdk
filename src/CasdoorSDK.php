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

namespace Casdoor;

use Casdoor\Auth\AuthService;
use Casdoor\Auth\JwtService;
use Casdoor\Services\UserService;

/**
 * CasdoorSDK is the main SDK class providing access to all services
 */
class CasdoorSDK
{
    private Client $client;
    private ?AuthService $authService = null;
    private ?JwtService $jwtService = null;
    private ?UserService $userService = null;

    /**
     * Create a new SDK instance
     *
     * @param string $endpoint The endpoint URL of Casdoor server
     * @param string $clientId The client ID
     * @param string $clientSecret The client secret
     * @param string $certificate The certificate content
     * @param string $organizationName The organization name
     * @param string $applicationName The application name
     */
    public function __construct(
        string $endpoint,
        string $clientId,
        string $clientSecret,
        string $certificate,
        string $organizationName,
        string $applicationName
    ) {
        $this->client = new Client(
            $endpoint,
            $clientId,
            $clientSecret,
            $certificate,
            $organizationName,
            $applicationName
        );
    }

    /**
     * Get the client instance
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Get the auth service
     *
     * @return AuthService
     */
    public function auth(): AuthService
    {
        if ($this->authService === null) {
            $this->authService = new AuthService($this->client);
        }
        return $this->authService;
    }

    /**
     * Get the JWT service
     *
     * @return JwtService
     */
    public function jwt(): JwtService
    {
        if ($this->jwtService === null) {
            $this->jwtService = new JwtService($this->client);
        }
        return $this->jwtService;
    }

    /**
     * Get the user service
     *
     * @return UserService
     */
    public function users(): UserService
    {
        if ($this->userService === null) {
            $this->userService = new UserService($this->client);
        }
        return $this->userService;
    }
}

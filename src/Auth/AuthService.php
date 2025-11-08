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

use Casdoor\Client;

/**
 * AuthService handles authentication operations
 */
class AuthService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get OAuth token using authorization code
     *
     * @param string $code The authorization code
     * @param string $state The state parameter
     * @return OAuthToken The OAuth token
     * @throws \Exception
     */
    public function getOAuthToken(string $code, string $state): OAuthToken
    {
        $params = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->client->clientId,
            'client_secret' => $this->client->clientSecret,
            'code' => $code,
        ];

        $url = $this->client->endpoint . '/api/login/oauth/access_token';
        
        try {
            $response = $this->client->doPostBytesRaw(
                $url,
                'application/x-www-form-urlencoded',
                http_build_query($params)
            );
            
            $data = json_decode($response, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to decode OAuth token response: ' . json_last_error_msg());
            }
            
            if (isset($data['access_token']) && strpos($data['access_token'], 'error:') === 0) {
                throw new \Exception(substr($data['access_token'], 7));
            }
            
            return new OAuthToken(
                $data['access_token'] ?? '',
                $data['token_type'] ?? 'Bearer',
                $data['expires_in'] ?? 0,
                $data['refresh_token'] ?? '',
                $data['scope'] ?? ''
            );
        } catch (\Exception $e) {
            throw new \Exception('Failed to get OAuth token: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Refresh OAuth token using refresh token
     *
     * @param string $refreshToken The refresh token
     * @return OAuthToken The new OAuth token
     * @throws \Exception
     */
    public function refreshOAuthToken(string $refreshToken): OAuthToken
    {
        $params = [
            'grant_type' => 'refresh_token',
            'client_id' => $this->client->clientId,
            'client_secret' => $this->client->clientSecret,
            'refresh_token' => $refreshToken,
        ];

        $url = $this->client->endpoint . '/api/login/oauth/refresh_token';
        
        try {
            $response = $this->client->doPostBytesRaw(
                $url,
                'application/x-www-form-urlencoded',
                http_build_query($params)
            );
            
            $data = json_decode($response, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to decode OAuth token response: ' . json_last_error_msg());
            }
            
            if (isset($data['access_token']) && strpos($data['access_token'], 'error:') === 0) {
                throw new \Exception(substr($data['access_token'], 7));
            }
            
            return new OAuthToken(
                $data['access_token'] ?? '',
                $data['token_type'] ?? 'Bearer',
                $data['expires_in'] ?? 0,
                $data['refresh_token'] ?? '',
                $data['scope'] ?? ''
            );
        } catch (\Exception $e) {
            throw new \Exception('Failed to refresh OAuth token: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Get the sign-in URL
     *
     * @param string $redirectUri The redirect URI after sign-in
     * @return string The sign-in URL
     */
    public function getSigninUrl(string $redirectUri): string
    {
        $params = [
            'client_id' => $this->client->clientId,
            'response_type' => 'code',
            'redirect_uri' => $redirectUri,
            'scope' => 'read',
            'state' => $this->client->applicationName,
        ];

        return sprintf(
            '%s/login/oauth/authorize?%s',
            $this->client->endpoint,
            http_build_query($params)
        );
    }

    /**
     * Get the sign-up URL
     *
     * @param bool $enablePassword Whether to enable password signup
     * @param string $redirectUri The redirect URI after sign-up
     * @return string The sign-up URL
     */
    public function getSignupUrl(bool $enablePassword = true, string $redirectUri = ''): string
    {
        if ($enablePassword) {
            return sprintf('%s/signup/%s', $this->client->endpoint, $this->client->applicationName);
        } else {
            return str_replace(
                '/login/oauth/authorize',
                '/signup/oauth/authorize',
                $this->getSigninUrl($redirectUri)
            );
        }
    }

    /**
     * Get user profile URL
     *
     * @param string $userName The user name
     * @param string $accessToken Optional access token
     * @return string The user profile URL
     */
    public function getUserProfileUrl(string $userName, string $accessToken = ''): string
    {
        $url = sprintf(
            '%s/users/%s/%s',
            $this->client->endpoint,
            $this->client->organizationName,
            $userName
        );

        if (!empty($accessToken)) {
            $url .= '?access_token=' . urlencode($accessToken);
        }

        return $url;
    }

    /**
     * Get my profile URL
     *
     * @param string $accessToken Optional access token
     * @return string The profile URL
     */
    public function getMyProfileUrl(string $accessToken = ''): string
    {
        $url = sprintf('%s/account', $this->client->endpoint);

        if (!empty($accessToken)) {
            $url .= '?access_token=' . urlencode($accessToken);
        }

        return $url;
    }
}

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

// Token has the same definition as https://github.com/casdoor/casdoor/blob/master/object/token.go
class Token
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';

    public string $application  = '';
    public string $organization = '';
    public string $user         = '';

    public string $code             = '';
    public string $accessToken      = '';
    public string $refreshToken     = '';
    public string $accessTokenHash  = '';
    public string $refreshTokenHash = '';
    public int    $expiresIn        = 0;
    public string $scope            = '';
    public string $tokenType        = '';
    public string $codeChallenge    = '';
    public bool   $codeIsUsed       = false;
    public int    $codeExpireIn     = 0;
}

class IntrospectTokenResult
{
    public bool   $active    = false;
    public string $clientId  = '';
    public string $username  = '';
    public string $tokenType = '';
    public int    $exp       = 0;
    public int    $iat       = 0;
    public int    $nbf       = 0;
    public string $sub       = '';
    public array  $aud       = [];
    public string $iss       = '';
    public string $jti       = '';
}

trait TokenTrait
{
    public function getTokens(): array
    {
        $url = $this->getUrl('get-tokens', ['owner' => 'admin']);
        return $this->doGetBytes($url);
    }

    public function getPaginationTokens(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = 'admin';
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-tokens', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getToken(string $name): ?array
    {
        $url = $this->getUrl('get-token', ['id' => 'admin/' . $name]);
        return $this->doGetBytes($url);
    }

    public function updateToken(Token $token): bool
    {
        return $this->modifyToken('update-token', $token, []);
    }

    public function updateTokenForColumns(Token $token, array $columns): bool
    {
        return $this->modifyToken('update-token', $token, $columns);
    }

    public function addToken(Token $token): bool
    {
        return $this->modifyToken('add-token', $token, []);
    }

    public function deleteToken(Token $token): bool
    {
        return $this->modifyToken('delete-token', $token, []);
    }

    public function introspectToken(string $token, string $tokenTypeHint): array
    {
        $params      = ['token' => $token, 'token_type_hint' => $tokenTypeHint];
        $url         = $this->getUrl('login/oauth/introspect');
        $postData    = json_encode($params, JSON_THROW_ON_ERROR);
        $response    = $this->doPost('login/oauth/introspect', [], $postData, true, false);
        return $response;
    }

    private function modifyToken(string $action, Token $token, array $columns): bool
    {
        $token->owner = 'admin';
        $queryMap     = ['id' => 'admin/' . $token->name];
        if (!empty($columns)) {
            $queryMap['columns'] = implode(',', $columns);
        }
        $postData = json_encode($token, JSON_THROW_ON_ERROR);
        $response = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

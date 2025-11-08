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

use Casdoor\Entities\User;

/**
 * JwtClaims represents the claims in a JWT token
 */
class JwtClaims
{
    public string $owner = '';
    public string $name = '';
    public string $id = '';
    public string $displayName = '';
    public string $email = '';
    public string $avatar = '';
    public string $phone = '';
    public string $accessToken = '';
    public string $tokenType = '';
    public string $refreshTokenType = '';
    public string $signinMethod = '';
    
    // Standard JWT claims
    public ?int $exp = null;
    public ?int $iat = null;
    public ?int $nbf = null;
    public ?string $iss = null;
    public ?string $sub = null;
    public ?string $aud = null;
    
    /**
     * @var array<string, mixed> Additional properties
     */
    public array $properties = [];

    /**
     * Check if token is a refresh token
     *
     * @return bool
     */
    public function isRefreshToken(): bool
    {
        return $this->refreshTokenType === 'refresh-token';
    }

    /**
     * Convert JWT claims to User object
     *
     * @return User
     */
    public function toUser(): User
    {
        $user = new User();
        $user->owner = $this->owner;
        $user->name = $this->name;
        $user->id = $this->id;
        $user->displayName = $this->displayName;
        $user->email = $this->email;
        $user->avatar = $this->avatar;
        $user->phone = $this->phone;
        
        return $user;
    }

    /**
     * Create JwtClaims from stdClass object
     *
     * @param \stdClass $obj
     * @return JwtClaims
     */
    public static function fromStdClass(\stdClass $obj): JwtClaims
    {
        $claims = new self();
        
        // Map standard properties
        $claims->owner = $obj->owner ?? '';
        $claims->name = $obj->name ?? '';
        $claims->id = $obj->id ?? '';
        $claims->displayName = $obj->displayName ?? '';
        $claims->email = $obj->email ?? '';
        $claims->avatar = $obj->avatar ?? '';
        $claims->phone = $obj->phone ?? '';
        $claims->accessToken = $obj->accessToken ?? '';
        $claims->tokenType = $obj->tokenType ?? '';
        $claims->refreshTokenType = $obj->TokenType ?? ''; // Note: capital T from Go SDK
        $claims->signinMethod = $obj->signinMethod ?? '';
        
        // Map standard JWT claims
        $claims->exp = $obj->exp ?? null;
        $claims->iat = $obj->iat ?? null;
        $claims->nbf = $obj->nbf ?? null;
        $claims->iss = $obj->iss ?? null;
        $claims->sub = $obj->sub ?? null;
        $claims->aud = $obj->aud ?? null;
        
        // Store any additional properties
        foreach ($obj as $key => $value) {
            if (!property_exists($claims, $key)) {
                $claims->properties[$key] = $value;
            }
        }
        
        return $claims;
    }

    /**
     * Convert to array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = [
            'owner' => $this->owner,
            'name' => $this->name,
            'id' => $this->id,
            'displayName' => $this->displayName,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'phone' => $this->phone,
            'accessToken' => $this->accessToken,
            'tokenType' => $this->tokenType,
            'refreshTokenType' => $this->refreshTokenType,
            'signinMethod' => $this->signinMethod,
            'exp' => $this->exp,
            'iat' => $this->iat,
            'nbf' => $this->nbf,
            'iss' => $this->iss,
            'sub' => $this->sub,
            'aud' => $this->aud,
        ];
        
        return array_merge($result, $this->properties);
    }
}

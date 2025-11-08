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
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * JwtService handles JWT token parsing and validation
 */
class JwtService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Parse and validate JWT token
     *
     * @param string $token The JWT token to parse
     * @return JwtClaims The parsed claims
     * @throws \Exception
     */
    public function parseJwtToken(string $token): JwtClaims
    {
        try {
            // Decode the token header to determine the algorithm
            $tks = explode('.', $token);
            if (count($tks) != 3) {
                throw new \Exception('Invalid JWT token format');
            }

            $headb64 = $tks[0];
            $header = json_decode(JWT::urlsafeB64Decode($headb64), true);
            
            if (!isset($header['alg'])) {
                throw new \Exception('JWT token missing algorithm');
            }

            $algorithm = $header['alg'];
            
            // Parse the public key based on algorithm
            $publicKey = $this->parsePublicKey($algorithm);
            
            // Decode the token
            $decoded = JWT::decode($token, new Key($publicKey, $algorithm));
            
            // Convert to JwtClaims object
            return JwtClaims::fromStdClass($decoded);
            
        } catch (\Exception $e) {
            throw new \Exception('Failed to parse JWT token: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Parse public key based on algorithm
     *
     * @param string $algorithm The JWT algorithm
     * @return resource|string The parsed public key
     * @throws \Exception
     */
    private function parsePublicKey(string $algorithm)
    {
        $certificate = $this->client->certificate;
        
        switch ($algorithm) {
            case 'RS256':
            case 'RS512':
                $key = openssl_pkey_get_public($certificate);
                if ($key === false) {
                    throw new \Exception('Failed to parse RSA public key');
                }
                return $key;
                
            case 'ES256':
            case 'ES512':
                $key = openssl_pkey_get_public($certificate);
                if ($key === false) {
                    throw new \Exception('Failed to parse EC public key');
                }
                return $key;
                
            default:
                throw new \Exception("Unsupported JWT algorithm: $algorithm");
        }
    }
}

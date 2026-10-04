<?php

// Copyright 2026 The Casdoor Authors. All Rights Reserved.
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

namespace Casdoor\Tests;

use Casdoor\Client;
use Casdoor\Exceptions\CasdoorException;
use Firebase\JWT\JWT;
use PHPUnit\Framework\TestCase;

class JwtTest extends TestCase
{
    public function testParseJwtToken(): void
    {
        $key       = openssl_pkey_new(['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
        $publicKey = openssl_pkey_get_details($key)['key'];
        openssl_pkey_export($key, $privateKey);

        $token = JWT::encode(['name' => 'admin', 'owner' => 'built-in', 'exp' => time() + 3600], $privateKey, 'RS256');

        $client = new Client('http://localhost:8000', 'id', 'secret', $publicKey, 'built-in', 'app-built-in');
        $claims = $client->parseJwtToken($token);

        $this->assertSame('admin', $claims['name']);
        $this->assertSame('built-in', $claims['owner']);
    }

    public function testRejectHmacSignedWithCertificate(): void
    {
        $key       = openssl_pkey_new(['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
        $publicKey = openssl_pkey_get_details($key)['key'];

        // a forged token signed with HS256 and the public key (which is not a secret) as the HMAC key
        $token = JWT::encode(['name' => 'admin', 'owner' => 'built-in', 'exp' => time() + 3600], $publicKey, 'HS256');

        $client = new Client('http://localhost:8000', 'id', 'secret', $publicKey, 'built-in', 'app-built-in');

        $this->expectException(CasdoorException::class);
        $client->parseJwtToken($token);
    }

    public function testParseInvalidJwtToken(): void
    {
        $client = new Client('http://localhost:8000', 'id', 'secret', 'cert', 'built-in', 'app-built-in');

        $this->expectException(CasdoorException::class);
        $client->parseJwtToken('invalid');
    }
}

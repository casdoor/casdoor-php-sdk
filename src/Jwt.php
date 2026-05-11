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

use Casdoor\Exceptions\CasdoorException;
use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;

trait JwtTrait
{
    public function parseJwtToken(string $token): array
    {
        $certificate = $this->certificate;
        if (empty($certificate)) {
            throw new CasdoorException('Certificate is empty');
        }

        // Detect algorithm from JWT header
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new CasdoorException('Invalid JWT token format');
        }

        $header = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[0])), true);
        $alg    = $header['alg'] ?? 'RS256';

        $decoded = FirebaseJWT::decode($token, new Key($certificate, $alg));

        return (array) $decoded;
    }
}

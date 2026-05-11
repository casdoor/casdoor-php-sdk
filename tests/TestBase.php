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

namespace Casdoor\Tests;

use Casdoor\Client;
use PHPUnit\Framework\TestCase;

class TestBase extends TestCase
{
    protected Client $client;

    protected function setUp(): void
    {
        $endpoint         = 'http://localhost:8000';
        $clientId         = 'YOUR_CLIENT_ID';
        $clientSecret     = 'YOUR_CLIENT_SECRET';
        $certificate      = file_get_contents(__DIR__ . '/public_key.pem') ?: '';
        $organizationName = 'built-in';
        $applicationName  = 'app-built-in';

        $this->client = new Client(
            $endpoint,
            $clientId,
            $clientSecret,
            $certificate,
            $organizationName,
            $applicationName
        );
    }
}

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

// Cert has the same definition as https://github.com/casdoor/casdoor/blob/master/object/cert.go
class Cert
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';

    public string $displayName             = '';
    public string $scope                   = '';
    public string $type                    = '';
    public string $cryptoAlgorithm         = '';
    public int    $bitSize                 = 0;
    public int    $expireInYears           = 0;

    public string $certificate            = '';
    public string $privateKey             = '';
    public string $authorityPublicKey     = '';
    public string $authorityRootPublicKey = '';
}

trait CertTrait
{
    public function getGlobalCerts(): array
    {
        $url = $this->getUrl('get-global-certs');
        return $this->doGetBytes($url);
    }

    public function getCerts(): array
    {
        $url = $this->getUrl('get-certs', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getCert(string $name): ?array
    {
        $url = $this->getUrl('get-cert', ['id' => $this->organizationName . '/' . $name]);
        return $this->doGetBytes($url);
    }

    public function addCert(Cert $cert): bool
    {
        return $this->modifyCert('add-cert', $cert);
    }

    public function updateCert(Cert $cert): bool
    {
        return $this->modifyCert('update-cert', $cert);
    }

    public function deleteCert(Cert $cert): bool
    {
        return $this->modifyCert('delete-cert', $cert);
    }

    private function modifyCert(string $action, Cert $cert): bool
    {
        if ($cert->owner === '') {
            $cert->owner = $this->organizationName;
        }
        $queryMap = ['id' => $cert->owner . '/' . $cert->name];
        $postData = json_encode($cert, JSON_THROW_ON_ERROR);
        $response = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

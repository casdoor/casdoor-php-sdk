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

namespace Casdoor;

trait MfaTrait
{
    /**
     * Starts setting up the MFA of the user, $mfaType is "app", "email" or "sms".
     * The data contains the secret and the recovery codes.
     */
    public function initiate(string $owner, string $mfaType, string $name): array
    {
        return $this->doPost('mfa/setup/initiate', [], ['owner' => $owner, 'mfaType' => $mfaType, 'name' => $name], true);
    }

    public function verify(string $owner, string $mfaType, string $name, string $secret, string $passcode): array
    {
        $form = ['owner' => $owner, 'mfaType' => $mfaType, 'name' => $name, 'secret' => $secret, 'passcode' => $passcode];
        return $this->doPost('mfa/setup/verify', [], $form, true);
    }

    public function enable(string $owner, string $mfaType, string $name, string $secret, string $recoveryCode = ''): array
    {
        $form = ['owner' => $owner, 'mfaType' => $mfaType, 'name' => $name, 'secret' => $secret, 'recoveryCode' => $recoveryCode];
        return $this->doPost('mfa/setup/enable', [], $form, true);
    }

    public function setPreferred(string $owner, string $mfaType, string $name, string $secret = ''): void
    {
        $this->doPost('set-preferred-mfa', [], ['owner' => $owner, 'mfaType' => $mfaType, 'name' => $name, 'secret' => $secret], true);
    }

    /**
     * Deletes all the MFA settings of the user.
     */
    public function delete(string $owner, string $name): void
    {
        $this->doPost('delete-mfa', ['owner' => $owner, 'name' => $name], '');
    }
}

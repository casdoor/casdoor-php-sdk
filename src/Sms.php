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

trait SmsTrait
{
    public function sendSms(string $content, array $receivers): void
    {
        $form     = compact('content', 'receivers');
        $postData = json_encode($form, JSON_THROW_ON_ERROR);
        $this->doPost('send-sms', [], $postData);
    }

    public function sendSmsByProvider(string $content, string $provider, array $receivers): void
    {
        $form     = compact('content', 'receivers');
        $postData = json_encode($form, JSON_THROW_ON_ERROR);
        $this->doPost('send-sms', ['provider' => $provider], $postData);
    }
}

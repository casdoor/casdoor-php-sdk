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

// Session has the same definition as https://github.com/casdoor/casdoor/blob/master/object/session.go
class Session
{
    public string $owner       = '';
    public string $name        = '';
    public string $application = '';
    public string $createdTime = '';

    public array $sessionId = [];
}

trait SessionTrait
{
    public function getSessions(): array
    {
        $url = $this->getUrl('get-sessions', ['owner' => $this->organizationName]);
        return $this->doGetBytes($url);
    }

    public function getPaginationSessions(int $p, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner']    = $this->organizationName;
        $queryMap['p']        = (string) $p;
        $queryMap['pageSize'] = (string) $pageSize;
        $url                  = $this->getUrl('get-sessions', $queryMap);
        $response             = $this->doGetResponse($url);
        return [$response['data'], (int) ($response['data2'] ?? 0)];
    }

    public function getSession(string $name, string $application): ?array
    {
        $sessionPkId = $this->organizationName . '/' . $name . '/' . $application;
        $url         = $this->getUrl('get-session', ['sessionPkId' => $sessionPkId]);
        return $this->doGetBytes($url);
    }

    public function addSession(Session $session): bool
    {
        return $this->modifySession('add-session', $session, []);
    }

    public function updateSession(Session $session): bool
    {
        return $this->modifySession('update-session', $session, []);
    }

    public function updateSessionForColumns(Session $session, array $columns): bool
    {
        return $this->modifySession('update-session', $session, $columns);
    }

    public function deleteSession(Session $session): bool
    {
        return $this->modifySession('delete-session', $session, []);
    }

    private function modifySession(string $action, Session $session, array $columns): bool
    {
        $session->owner = $this->organizationName;
        $queryMap       = ['id' => $session->owner . '/' . $session->name];
        if (!empty($columns)) {
            $queryMap['columns'] = implode(',', $columns);
        }
        $postData = json_encode($session, JSON_THROW_ON_ERROR);
        $response = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}

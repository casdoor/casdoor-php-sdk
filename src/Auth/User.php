<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;
use Casdoor\Exceptions\CasdoorException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/**
 * Class User.
 *
 * @author ab1652759879@gmail.com
 */
class User
{
    public string $owner;
    public string $name;
    public string $createdTime;
    public string $updatedTime;

    public string $id;
    public string $type;
    public string $password;
    public string $displayName;
    public string $avatar;
    public string $email;
    public string $phone;
    public string $affiliation;
    public string $tag;
    public string $language;
    public int $score;
    public bool $isAdmin;
    public bool $isGlobalAdmin;
    public bool $isForbidden;
    public string $hash;
    public string $preHash;

    public string $github;
    public string $google;
    public string $qq;
    public string $wechat;

    public array $properties;

    public AuthConfig $authConfig;

    public function initConfig(string $endpoint, string $clientId, string $clientSecret, string $jwtSecret, string $organizationName): void
    {
        $authConfig = new AuthConfig($endpoint, $clientId, $clientSecret, $jwtSecret, $organizationName);
        global $authConfig;
        $this->authConfig =& $authConfig;
    }

    public function getUsers(): array
    {
        $url = sprintf("%s/api/get-users?owner=%s&clientId=%s&clientSecret=%s", $this->authConfig->endpoint, $this->authConfig->organizationName, $this->authConfig->clientId, $this->authConfig->clientSecret);
        $bytes = Util::getBytes($url);
        $users = json_decode($bytes->__toString());
        return $users;
    }

    public function getUser(string $name): self
    {
        $url = sprintf("%s/api/get-user?id=%s/%s&clientId=%s&clientSecret=%s", $this->authConfig->endpoint, $this->authConfig->organizationName, $name, $this->authConfig->clientId, $this->authConfig->clientSecret);
        $bytes = Util::getBytes($url);
        $user = json_decode($bytes->__toString());
        return $user;
    }

    public function modifyUser(string $method, User $user): bool
    {
        $authConfig = $GLOBALS['authConfig'];
        $user->owner = $authConfig->organizationName;
    
        $url = sprintf("%s/api/%s?id=%s/%s&clientId=%s&clientSecret=%s", $authConfig->Endpoint, $method, $user->owner, $user->name, $authConfig->clientId, $authConfig->clientSecret);
        $userByte = json_encode($user);
        if ($userByte === false) {
            throw new CasdoorException("json_encode fails");
        }
    
        $client = new Client();
        $request = new Request('POST', $url, ['content-type' => 'text/plain;charset=UTF-8'], $userByte);
        $resp = $client->send($request);
        $respByte = $resp->getBody();
        $response = json_decode($respByte->__toString());
    
        if ($response->data === "Affected") {
            return true;
        }
        return false;
    }

    public function updateUser(User $user): bool
    {
        return $this->modifyUser("update-user", $user);
    }

    public function addUser(User $user): bool
    {
        return $this->modifyUser("add-user", $user);
    }

    public function deleteUser(User $user): bool
    {
        return $this->modifyUser("delete-user", $user);
    }
}

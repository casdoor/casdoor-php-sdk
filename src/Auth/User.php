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

    public static $authConfig;

    public function initConfig(string $endpoint, string $clientId, string $clientSecret, string $jwtSecret, string $organizationName): void
    {
        self::$authConfig = new AuthConfig($endpoint, $clientId, $clientSecret, $jwtSecret, $organizationName);
    }

    public function getUsers(): array
    {
        $url = sprintf("%s/api/get-users?owner=%s&clientId=%s&clientSecret=%s", self::$authConfig->endpoint, self::$authConfig->organizationName, self::$authConfig->clientId, self::$authConfig->clientSecret);
        $stream = Util::getStream($url);
        $users = json_decode($stream->__toString());
        return $users;
    }

    public function getUser(string $name): array
    {
        $url = sprintf("%s/api/get-user?id=%s/%s&clientId=%s&clientSecret=%s", self::$authConfig->endpoint, self::$authConfig->organizationName, $name, self::$authConfig->clientId, self::$authConfig->clientSecret);
        $stream = Util::getStream($url);
        $user = json_decode($stream->__toString(), true);
        return $user;
    }

    public function modifyUser(string $method, User $user): bool
    {
        $user->owner = self::$authConfig->organizationName;
    
        $url = sprintf("%s/api/%s?id=%s/%s&clientId=%s&clientSecret=%s", self::$authConfig->endpoint, $method, $user->owner, $user->name, self::$authConfig->clientId, self::$authConfig->clientSecret);
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

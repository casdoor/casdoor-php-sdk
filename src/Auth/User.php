<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;
use Casdoor\Exceptions\CasdoorException;

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
        $queryMap = [
            'owner' => self::$authConfig->organizationName
        ];

        $url = Util::getUrl('get-users', $queryMap, self::$authConfig);

        $url = sprintf("%s/api/get-users?owner=%s&clientId=%s&clientSecret=%s", self::$authConfig->endpoint, self::$authConfig->organizationName, self::$authConfig->clientId, self::$authConfig->clientSecret);
        $stream = Util::doGetStream($url);
        $users = json_decode($stream->__toString());
        return $users;
    }

    public function getUser(string $name): array
    {
        $queryMap = [
            'id' => sprintf('%s/%s', self::$authConfig->organizationName, $name),
        ];

        $url = Util::getUrl('get-user', $queryMap, self::$authConfig);

        $stream = Util::doGetStream($url);
        $user = json_decode($stream->__toString(), true);
        return $user;
    }

    public function modifyUser(string $action, User $user): array
    {
        $user->owner = self::$authConfig->organizationName;
        $queryMap = [
            'id' => sprintf('%s/%s', $user->owner, $user->name)
        ];

        $postData = json_encode($user);
        if ($postData === false) {
            throw new CasdoorException('json_encode fails');
        }

        $response = Util::doPost($action, $queryMap, self::$authConfig, $postData);
        return [$response, $response->data === 'Affected'];
    }

    public function updateUser(User $user): bool
    {
        list($response, $affected) = $this->modifyUser('update-user', $user);
        return $affected;
    }

    public function addUser(User $user): bool
    {
        list($response, $affected) = $this->modifyUser('add-user', $user);
        return $affected;
    }

    public function deleteUser(User $user): bool
    {
        list($response, $affected) = $this->modifyUser('delete-user', $user);
        return $affected;
    }

    public function checkUserPassword(User $user):bool
    {
        list($response, $affected) = $this->modifyUser('delete-user', $user);
        return $response->status == 'ok';
    }
}

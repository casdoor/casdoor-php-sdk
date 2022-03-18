<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

/**
 * Class User.
 *
 * @author ab1652759879@gmail.com
 */
class User
{
    /**
     * @var string
     */
    public $owner;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $createdTime;

    /**
     * @var string
     */
    public $updatedTime;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $passwordSalt;

    /**
     * @var string
     */
    public $displayName;

    /**
     * @var string
     */
    public $avatar;

    /**
     * @var string
     */
    public $permanentAvatar;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $location;

    /**
     * @var array
     */
    public $address;

    /**
     * @var string
     */
    public $affiliation;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $idCardType;

    /**
     * @var string
     */
    public $idCard;

    /**
     * @var string
     */
    public $homePage;

    /**
     * @var string
     */
    public $bio;

    /**
     * @var string
     */
    public $tag;

    /**
     * @var string
     */
    public $region;

    /**
     * @var string
     */
    public $language;

    /**
     * @var string
     */
    public $gender;

    /**
     * @var string
     */
    public $brithday;

    /**
     * @var string
     */
    public $education;

    /**
     * @var int
     */
    public $score;

    /**
     * @var int
     */
    public $ranking;

    /**
     * @var bool
     */
    public $isDefaultAvatar;

    /**
     * @var bool
     */
    public $isOnline;

    /**
     * @var bool
     */
    public $isAdmin;

    /**
     * @var bool
     */
    public $isGlobalAdmin;

    /**
     * @var bool
     */
    public $isForbidden;

    /**
     * @var bool
     */
    public $isDeleted;

    /**
     * @var string
     */
    public $signupApplication;

    /**
     * @var string
     */
    public $hash;

    /**
     * @var string
     */
    public $preHash;

    /**
     * @var string
     */
    public $createdIp;

    /**
     * @var string
     */
    public $lastSigninTime;

    /**
     * @var string
     */
    public $lastSigninIp;

    /**
     * @var string
     */
    public $github;

    /**
     * @var string
     */
    public $google;

    /**
     * @var string
     */
    public $qq;

    /**
     * @var string
     */
    public $wechat;

    /**
     * @var string
     */
    public $facebook;

    /**
     * @var string
     */
    public $dingtalk;

    /**
     * @var string
     */
    public $weibo;

    /**
     * @var string
     */
    public $gitee;

    /**
     * @var string
     */
    public $linkedin;

    /**
     * @var string
     */
    public $wecom;

    /**
     * @var string
     */
    public $lark;

    /**
     * @var string
     */
    public $gitlab;

    /**
     * @var string
     */
    public $ldap;

    /**
     * @var array
     */
    public array  $properties;

    public static $authConfig;

    public static function initConfig(string $endpoint, string $clientId, string $clientSecret, string $jwtSecret, string $organizationName, string $applicationName): void
    {
        self::$authConfig = new AuthConfig($endpoint, $clientId, $clientSecret, $jwtSecret, $organizationName, $applicationName);
    }

    public static function getUsers(): array
    {
        $queryMap = [
            'owner' => self::$authConfig->organizationName
        ];

        $url = Util::getUrl('get-users', $queryMap, self::$authConfig);

        $url = sprintf("%s/api/get-users?owner=%s&clientId=%s&clientSecret=%s", self::$authConfig->endpoint, self::$authConfig->organizationName, self::$authConfig->clientId, self::$authConfig->clientSecret);
        $stream = Util::doGetStream($url, self::$authConfig);
        $users = json_decode($stream->__toString());
        return $users;
    }

    public static function getSortedUsers(string $sorter, int $limit): array
    {
        $queryMap = [
            'owner'  => self::$authConfig->organizationName,
            'sorter' => $sorter,
            'limit'  => strval($limit)
        ];
    
        $url = Util::getUrl('get-sorted-users', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $users = json_decode($stream->__toString(), true);
        return $users;
    }

    public static function getUserCount(int $isOnline): int
    {
        $queryMap = [
            'owner'    => self::$authConfig->organizationName,
            'isOnline' => $isOnline,
        ];
    
        $url = Util::getUrl('get-user-count', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $count = json_decode($stream->__toString(), true, 512, JSON_THROW_ON_ERROR);
        return $count;
    }

    public static function getUser(string $name): array
    {
        $queryMap = [
            'id' => sprintf('%s/%s', self::$authConfig->organizationName, $name),
        ];

        $url = Util::getUrl('get-user', $queryMap, self::$authConfig);

        $stream = Util::doGetStream($url, self::$authConfig);
        $user = json_decode($stream->__toString(), true);
        return $user;
    }

    public static function getUserByEmail(string $email): array
    {
        $queryMap = [
            'owner' => self::$authConfig->organizationName,
            'email' => $email,
        ];
    
        $url = Util::getUrl('get-user', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $user = json_decode($stream->__toString(), true, 512, JSON_THROW_ON_ERROR);
        return $user;
    }

    /**
     * modifyUser is an encapsulation of user CUD(Create, Update, Delete) operations.
     * possible actions are `add-user`, `update-user`, `delete-user`...
     *
     * @param string $action
     * @param User   $user
     * @param array  $columns
     *
     * @return array
     */
    public static function modifyUser(string $action, User $user, array $columns): array
    {
        $user->owner = self::$authConfig->organizationName;
        $queryMap = [
            'id' => sprintf('%s/%s', $user->owner, $user->name)
        ];

        if (count($columns) != 0) {
            $queryMap['columns'] = implode(',', $columns);
        }

        $postData = json_encode($user, JSON_THROW_ON_ERROR);

        $response = Util::doPost($action, $queryMap, self::$authConfig, $postData, false);
        return [$response, $response->data === 'Affected'];
    }

    public static function updateUser(User $user): bool
    {
        list($response, $affected) = self::modifyUser('update-user', $user, []);
        return $affected;
    }

    public static function updateUserForColumns(User $user, array $columns): bool
    {
        list($response, $affected) = self::modifyUser('update-user', $user, $columns);
        return $affected;
    }

    public static function addUser(User $user): bool
    {
        list($response, $affected) = self::modifyUser('add-user', $user, []);
        return $affected;
    }

    public static function deleteUser(User $user): bool
    {
        list($response, $affected) = self::modifyUser('delete-user', $user, []);
        return $affected;
    }

    public static function checkUserPassword(User $user):bool
    {
        list($response, $affected) = self::modifyUser('check-user-password', $user, []);
        return $response->status == 'ok';
    }
}

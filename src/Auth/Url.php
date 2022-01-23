<?php

declare(strict_types=1);

namespace Casdoor\Auth;

/**
 * Class Url is used to get some common url of casdoor
 *
 * @author ab1652759879@gmail.com
 */
class Url
{
    public static function getSignupUrl(bool $enablePassword, string $redirectUri, AuthConfig $authConfig): string
    {
        // redirectUri can be empty string if enablePassword == true (only password enabled signup page is required)
        if ($enablePassword) {
            return sprintf('%s/signup/%s', $authConfig->endpoint, $authConfig->applicationName);
        } else {
            return str_replace('/login/oauth/authorize', '/signup/oauth/authorize', self::getSigninUrl($redirectUri, $authConfig));
        }
    }

    public function getSigninUrl(string $redirectUri, AuthConfig $authConfig): string
    {
        // $origin = 'https://door.casbin.com';
        // $redirectUri = sprintf('%s/callback', $origin);
        $scope = 'read';
        $state = $authConfig->applicationName;
        return sprintf('%s/login/oauth/authorize?client_id=%s&response_type=code&redirect_uri=%s&scope=%s&state=%s', $authConfig->endpoint, $authConfig->clientId, urlencode($redirectUri), $scope, $state);
    }

    public static function getUserProfileUrl(string $userName, string $accessToken, AuthConfig $authConfig): string
    {
        $param = '';
        if ($accessToken != '') {
            $param = sprintf('?access_token=%s', $accessToken);
        }
        return sprintf('%s/users/%s/%s%s', $authConfig->endpoint, $authConfig->organizationName, $userName, $param);
    }

    public static function getMyProfileUrl(string $accessToken, AuthConfig $authConfig): string
    {
        $param = '';
        if ($accessToken != '') {
            $param = sprintf('?access_token=%s', $accessToken);
        }
        return sprintf('%s/account%s', $authConfig->endpoint, $param);
    }
}

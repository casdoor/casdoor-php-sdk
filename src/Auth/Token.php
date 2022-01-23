<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use League\OAuth2\Client\Token\AccessTokenInterface;
use League\OAuth2\Client\Provider\GenericProvider;

/**
 * Class Token.
 *
 * @author ab1652759879@gmail.com
 */
class Token
{
    /**
     * GetOAuthToken gets the pivotal and necessary secret to interact with the Casdoor server
     *
     * @param string $code
     * @param string $state
     *
     * @return AccessTokenInterface
     */
    public function getOAuthToken(string $code, string $state): AccessTokenInterface
    {
        $authConfig = User::$authConfig;
        $provider = new GenericProvider([
            'clientId'                => $authConfig->clientId,
            'clientSecret'            => $authConfig->clientSecret,
            'urlAuthorize'            => sprintf("%s/api/login/oauth/authorize", $authConfig->endpoint),
            'urlAccessToken'          => sprintf("%s/api/login/oauth/access_token", $authConfig->endpoint),
            'urlResourceOwnerDetails' => sprintf("%s/api/get-account", $authConfig->endpoint),
        ]);
        $accessToken = $provider->getAccessToken('authorization_code', ['code' => $code]);
        return $accessToken;
    }
}

<?php

declare(strict_types=1);

namespace Casdoor\Auth;

/**
 * Class AuthConfig.
 *
 * @author ab1652759879@gmail.com
 */
class AuthConfig
{
    public string $endpoint;
    public string $clientId;
    public string $clientSecret;
    public string $JwtPublicKey;
    public string $organizationName;
    public string $applicationName;

    public function __construct(string $endpoint, string $clientId, string $clientSecret, string $jwtPublicKey, string $organizationName, string $applicationName)
    {
        $this->endpoint         = $endpoint;
        $this->clientId         = $clientId;
        $this->clientSecret     = $clientSecret;
        $this->jwtPublicKey     = $jwtPublicKey;
        $this->organizationName = $organizationName;
        $this->applicationName  = $applicationName;
    }
}

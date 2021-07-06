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
    public string $jwtSecret;
    public string $organizationName;

    public function __construct(string $endpoint, string $clientId, string $clientSecret, string $jwtSecret, string $organizationName)
    {
        $this->endpoint = $endpoint;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->jwtSecret = $jwtSecret;
        $this->organizationName = $organizationName;
    }
}

<?php

declare(strict_types=1);

namespace Casdoor\Auth;

/**
 * AuthConfig is the core configuration.
 * The first step to use this SDK is to use the InitConfig function to initialize an instance of authConfig.
 *
 * @author ab1652759879@gmail.com
 */
class AuthConfig
{
    /**
     * @var string
     */
    public $endpoint;

    /**
     * @var string
     */
    public $clientId;

    /**
     * @var string
     */
    public $clientSecret;

    /**
     * @var string
     */
    public $JwtPublicKey;

    /**
     * @var string
     */
    public $organizationName;

    /**
     * @var string
     */
    public $applicationName;

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

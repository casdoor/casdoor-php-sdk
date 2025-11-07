<?php

declare(strict_types=1);

namespace Casdoor\Tests;

use Casdoor\Auth\AuthConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test case for AuthConfig class
 */
class AuthConfigTest extends TestCase
{
    /**
     * Test AuthConfig constructor
     */
    public function testAuthConfigConstructor(): void
    {
        $endpoint = 'http://localhost:8000';
        $clientId = 'test_client_id';
        $clientSecret = 'test_client_secret';
        $certificate = 'test_certificate';
        $organizationName = 'test_org';
        $applicationName = 'test_app';
        
        $config = new AuthConfig(
            $endpoint,
            $clientId,
            $clientSecret,
            $certificate,
            $organizationName,
            $applicationName
        );
        
        $this->assertEquals($endpoint, $config->endpoint);
        $this->assertEquals($clientId, $config->clientId);
        $this->assertEquals($clientSecret, $config->clientSecret);
        $this->assertEquals($certificate, $config->certificate);
        $this->assertEquals($organizationName, $config->organizationName);
        $this->assertEquals($applicationName, $config->applicationName);
    }

    /**
     * Test AuthConfig with empty values
     */
    public function testAuthConfigWithEmptyValues(): void
    {
        $config = new AuthConfig('', '', '', '', '', '');
        
        $this->assertEquals('', $config->endpoint);
        $this->assertEquals('', $config->clientId);
        $this->assertEquals('', $config->clientSecret);
        $this->assertEquals('', $config->certificate);
        $this->assertEquals('', $config->organizationName);
        $this->assertEquals('', $config->applicationName);
    }

    /**
     * Test AuthConfig with test constants
     */
    public function testAuthConfigWithTestConstants(): void
    {
        $config = new AuthConfig(
            TestUtil::TEST_ENDPOINT,
            TestUtil::TEST_CLIENT_ID,
            TestUtil::TEST_CLIENT_SECRET,
            TestUtil::TEST_JWT_PUBLIC_KEY,
            TestUtil::TEST_ORGANIZATION,
            TestUtil::TEST_APPLICATION
        );
        
        $this->assertEquals(TestUtil::TEST_ENDPOINT, $config->endpoint);
        $this->assertEquals(TestUtil::TEST_CLIENT_ID, $config->clientId);
        $this->assertEquals(TestUtil::TEST_CLIENT_SECRET, $config->clientSecret);
        $this->assertEquals(TestUtil::TEST_JWT_PUBLIC_KEY, $config->certificate);
        $this->assertEquals(TestUtil::TEST_ORGANIZATION, $config->organizationName);
        $this->assertEquals(TestUtil::TEST_APPLICATION, $config->applicationName);
    }
}

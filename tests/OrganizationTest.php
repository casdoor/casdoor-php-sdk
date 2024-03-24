<?php

namespace Casdoor\Tests;

use PHPUnit\Framework\TestCase;
use Casdoor\Auth\AuthConfig;
use Casdoor\Auth\Organization;

class OrganizationTest extends TestCase
{
    protected $authConfig;

    public function initConfig()
    {
        $endpoint = 'http://127.0.0.1:8000';
        $clientId = 'c64b12723aefb65a88ce';
        $clientSecret = 'c0c9d483a87332751b2564635765d71c9f6a2e83';
        $jwtSecret = file_get_contents(dirname(__FILE__) . '/public_key.pem');
        $organizationName = 'built-in';
        $applicationName = 'testApp';
        User::initConfig($endpoint, $clientId, $clientSecret, $jwtSecret, $organizationName, $applicationName);
    }

    public function testAddOrganization()
    {
        $organization = new Organization('test_owner', 'test_organization');

        $organizationHandler = new Organization();

        $result = $organizationHandler->addOrganization($organization,  User::$authConfig);

        $this->assertIsBool($result);
    }

    public function testDeleteOrganization()
    {
        $organizationHandler = new Organization();

        $result = $organizationHandler->deleteOrganization('test_organization', User::$authConfig);

        $this->assertIsBool($result);
    }
}

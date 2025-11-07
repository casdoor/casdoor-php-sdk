<?php

declare(strict_types=1);

namespace Casdoor\Tests;

use Casdoor\Auth\Organization;
use Casdoor\Auth\AuthConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test case for Organization class
 */
class OrganizationTest extends TestCase
{
    private AuthConfig $authConfig;

    protected function setUp(): void
    {
        $this->authConfig = new AuthConfig(
            TestUtil::TEST_ENDPOINT,
            TestUtil::TEST_CLIENT_ID,
            TestUtil::TEST_CLIENT_SECRET,
            TestUtil::TEST_JWT_PUBLIC_KEY,
            TestUtil::TEST_ORGANIZATION,
            TestUtil::TEST_APPLICATION
        );
    }

    /**
     * Test Organization constructor
     */
    public function testOrganizationConstructor(): void
    {
        $owner = 'test_owner';
        $name = 'test_org';
        
        $org = new Organization($owner, $name);
        
        $this->assertEquals($owner, $org->owner);
        $this->assertEquals($name, $org->name);
    }

    /**
     * Test Organization properties
     */
    public function testOrganizationProperties(): void
    {
        $org = new Organization('owner', 'name');
        
        $org->displayName = 'Test Organization';
        $org->websiteUrl = 'https://example.com';
        $org->favicon = 'https://example.com/favicon.ico';
        $org->passwordType = 'bcrypt';
        $org->phonePrefix = '+1';
        $org->enableSoftDeletion = true;
        
        $this->assertEquals('Test Organization', $org->displayName);
        $this->assertEquals('https://example.com', $org->websiteUrl);
        $this->assertEquals('https://example.com/favicon.ico', $org->favicon);
        $this->assertEquals('bcrypt', $org->passwordType);
        $this->assertEquals('+1', $org->phonePrefix);
        $this->assertTrue($org->enableSoftDeletion);
    }

    /**
     * Test adding organization
     *
     * @group integration
     */
    public function testAddOrganization(): void
    {
        $this->markTestSkipped('Requires live Casdoor server connection');
        
        $name = TestUtil::getRandomName('Org');
        $org = new Organization('admin', $name);
        $org->displayName = 'Test Organization ' . $name;
        $org->createdTime = TestUtil::getCurrentTime();
        
        $result = $org->addOrganization($org, $this->authConfig);
        $this->assertTrue($result);
        
        // Clean up
        $org->deleteOrganization($name, $this->authConfig);
    }

    /**
     * Test deleting organization
     *
     * @group integration
     */
    public function testDeleteOrganization(): void
    {
        $this->markTestSkipped('Requires live Casdoor server connection');
        
        $name = TestUtil::getRandomName('Org');
        $org = new Organization('admin', $name);
        
        // First add it
        $org->addOrganization($org, $this->authConfig);
        
        // Then delete it
        $result = $org->deleteOrganization($name, $this->authConfig);
        $this->assertTrue($result);
    }

    /**
     * Test adding organization with empty owner defaults to 'admin'
     *
     * @group integration
     */
    public function testAddOrganizationDefaultOwner(): void
    {
        $this->markTestSkipped('Requires live Casdoor server connection');
        
        $name = TestUtil::getRandomName('Org');
        $org = new Organization('', $name);
        $org->displayName = 'Test Organization';
        
        $result = $org->addOrganization($org, $this->authConfig);
        $this->assertTrue($result);
        
        // Verify owner was set to 'admin'
        $this->assertEquals('admin', $org->owner);
        
        // Clean up
        $org->deleteOrganization($name, $this->authConfig);
    }
}

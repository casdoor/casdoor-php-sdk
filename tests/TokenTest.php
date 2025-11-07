<?php

declare(strict_types=1);

namespace Casdoor\Tests;

use Casdoor\Auth\Token;
use Casdoor\Auth\User;
use PHPUnit\Framework\TestCase;

/**
 * Test case for Token class
 */
class TokenTest extends TestCase
{
    protected function setUp(): void
    {
        User::initConfig(
            TestUtil::TEST_ENDPOINT,
            TestUtil::TEST_CLIENT_ID,
            TestUtil::TEST_CLIENT_SECRET,
            TestUtil::TEST_JWT_PUBLIC_KEY,
            TestUtil::TEST_ORGANIZATION,
            TestUtil::TEST_APPLICATION
        );
    }

    /**
     * Test Token object instantiation
     */
    public function testTokenObjectCreation(): void
    {
        $token = new Token();
        $this->assertInstanceOf(Token::class, $token);
    }

    /**
     * Test getting OAuth token
     *
     * @group integration
     */
    public function testGetOAuthToken(): void
    {
        $this->markTestSkipped('Requires live Casdoor server connection and valid authorization code');
        
        $code = 'test_authorization_code';
        $state = 'test_state';
        
        $token = new Token();
        $accessToken = $token->getOAuthToken($code, $state);
        
        $this->assertNotNull($accessToken);
        $this->assertIsString($accessToken->getToken());
    }
}

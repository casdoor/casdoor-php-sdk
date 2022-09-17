# casdoor-php-sdk

[![Latest Stable Version](http://poser.pugx.org/casdoor/casdoor-php-sdk/v)](https://packagist.org/packages/casdoor/casdoor-php-sdk) [![Total Downloads](http://poser.pugx.org/casdoor/casdoor-php-sdk/downloads)](https://packagist.org/packages/casdoor/casdoor-php-sdk) [![Latest Unstable Version](http://poser.pugx.org/casdoor/casdoor-php-sdk/v/unstable)](https://packagist.org/packages/casdoor/casdoor-php-sdk) [![License](http://poser.pugx.org/casdoor/casdoor-php-sdk/license)](https://packagist.org/packages/casdoor/casdoor-php-sdk) [![PHP Version Require](http://poser.pugx.org/casdoor/casdoor-php-sdk/require/php)](https://packagist.org/packages/casdoor/casdoor-php-sdk)

Chinese: [README_zh-CN.md](README_zh-CN.md)

Casdoor PHP SDK will allow you to easily connect your application to Casdoor authentication system without having to start from scratch.

# Step 1: Composer install casdoor-php-sdk 

In your php application directory, run the following command:

```
composer require casdoor/casdoor-php-sdk
```

Or use composer.json to add the following code:

```
{
    "require": {
        "vendor/package": "*",
    }
}
```

Then run composer install to make it take effect.
Create a OauthTest. Php file and import the SDK package.

```php
<?php

namespace Casdoor\Tests;

use PHPUnit\Framework\TestCase;
use Casdoor\Auth\Jwt;
use Casdoor\Auth\Token;
use Casdoor\Auth\User;

class OauthTest extends TestCase
{
  public function xxx(){}
}
```

# Step 2: configure parameters

for more information, see initConfig()

| Parameter           | required | description                                                                                            |
| ---------------- | -------- | ----------------------------------------------------------------------------------------------- |
| endpoint         | Yes      | The back-end API address of the Casdoor, for example:http://localhost:8000                      |
| clientId         | Yes      | The client ID of the current application.                                                       |
| clientSecret     | Yes      | The client key of the current application.                                                      |
| certificate      | Yes      | Public key certificate in x509 format under the certificate module (file format public_key.pem) |
| organizationName | Yes      | The organization name of the current application configuration.                                 |
| applicationName  | Yes      | The name of the current application.                                                            |

Reference code:

```php
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
```

# Step 3: obtain the user JWT token

after you log on to the logon page, the page is redirected to a link with code and state, such:[https://forum.casbin.com?code=xxx&state=yyyy](https://forum.casbin.com?code=xxx&state=yyyy.)
In the sample file, enter code and state, call the testGetOauthToken() method, and parse the jwt token.

```php
public $code = "cc78dc9953ca6ae6ab58";
public $state = "casdoor";
public function testGetOauthToken()
{
    $this->initConfig();
    $token = new Token();
    $accessToken = $token->getOAuthToken($this->code, $this->state);
    $this->assertIsString($accessToken->getToken());
}
```

The JWT token represents the user's identity and has the right to call relevant APIs.

# Step 4: verify and parse the user token

When a user passes in a JWT token, testParseJwtToken function calls the public key to verify the JWT token. If the verification is passed, the Array object is returned, which contains the account information of the user.

```php
public function testParseJwtToken()
{
    $this->initConfig();
    $token = "eyJhxxxx";	// from testGetOauthToken()
    $jwt = new Jwt();
    $result = $jwt->parseJwtToken($token, User::$authConfig);
    $this->assertIsArray();
}
```

# Step 5: update user information

testModifyUser call the application configuration (non-user token) as the update permission to perform CURD operations on user information.

```php
public function testModifyUser()
{
    $this->initConfig();
    $user = new User();

    # Delete User
    $user->name = 'user_hn99qa';
    $response = $user->deleteUser($user);
    $this->assertTrue($response);

    # Add User
    $response = $user->addUser($user);
    $this->assertTrue($response);

    # Update User
    $user->phone = 'phone';
    $user->displayName = 'display name';
    $response = $user->updateUser($user);
    $this->assertTrue($response);
}
```

# Others: User interaction

- User::getUser() , obtain User information by User name
- User::getUsers() to obtain information about all users.
- User::getUserCount() to obtain the current number of users.

# Casdoor PHP SDK

[![Latest Stable Version](http://poser.pugx.org/casdoor/casdoor-php-sdk/v)](https://packagist.org/packages/casdoor/casdoor-php-sdk)
[![Total Downloads](http://poser.pugx.org/casdoor/casdoor-php-sdk/downloads)](https://packagist.org/packages/casdoor/casdoor-php-sdk)
[![License](http://poser.pugx.org/casdoor/casdoor-php-sdk/license)](https://packagist.org/packages/casdoor/casdoor-php-sdk)
[![PHP Version Require](http://poser.pugx.org/casdoor/casdoor-php-sdk/require/php)](https://packagist.org/packages/casdoor/casdoor-php-sdk)

Casdoor PHP SDK is the official PHP client library for [Casdoor](https://casdoor.org/), which allows you to easily integrate Casdoor authentication and authorization into your PHP applications. This SDK provides a comprehensive set of APIs to interact with Casdoor server, enabling you to manage users, organizations, applications, roles, permissions, and much more.

## 📋 Table of Contents

- [Features](#-features)
- [Installation](#-installation)
- [Quick Start](#-quick-start)
- [Configuration](#-configuration)
- [Authentication](#-authentication)
- [Resource Management](#-resource-management)
- [API Reference](#-api-reference)
- [Examples](#-examples)
- [Documentation](#-documentation)
- [Contributing](#-contributing)
- [License](#-license)

## ✨ Features

- **OAuth 2.0 Authentication**: Complete OAuth 2.0 flow implementation with token management
- **User Management**: Create, read, update, and delete users with comprehensive profile support
- **Organization Management**: Manage organizations and organizational structures
- **Role-Based Access Control (RBAC)**: Full support for roles, permissions, and policies
- **Resource Management**: Manage applications, certificates, providers, and more
- **Session Management**: Handle user sessions and authentication states
- **Multi-Factor Authentication (MFA)**: Support for TOTP and other MFA methods
- **Email & SMS**: Send verification codes and notifications
- **Payment & Subscriptions**: Handle user payments and subscription management
- **Webhook Support**: Configure and manage webhooks for event notifications

## 📦 Installation

To install the Casdoor PHP SDK, you need PHP 7.4 or higher. Run the following command in your PHP project:

```bash
composer require casdoor/casdoor-php-sdk
```

## 🚀 Quick Start

Here's a minimal example to get you started with Casdoor PHP SDK:

```php
<?php

require_once 'vendor/autoload.php';

use Casdoor\Auth\User;
use Casdoor\Auth\Token;
use Casdoor\Auth\Jwt;

// Initialize the SDK with your Casdoor instance configuration
User::initConfig(
    'http://localhost:8000',           // endpoint
    'CLIENT_ID',                        // clientId
    'CLIENT_SECRET',                    // clientSecret
    'CERTIFICATE_CONTENT',              // certificate (x509 format)
    'my-organization',                  // organizationName
    'my-application'                    // applicationName
);

// Get all users
$users = User::getUsers();
echo "Found " . count($users) . " users\n";
```

## ⚙️ Configuration

The SDK requires six configuration parameters:

### Configuration Parameters

| Parameter        | Required | Description                                                  |
|------------------|----------|--------------------------------------------------------------|
| endpoint         | Yes      | Casdoor server URL (e.g., `http://localhost:8000`)          |
| clientId         | Yes      | Application client ID from Casdoor                           |
| clientSecret     | Yes      | Application client secret from Casdoor                       |
| certificate      | Yes      | x509 certificate content of your application (PEM format)    |
| organizationName | Yes      | Organization name in Casdoor                                 |
| applicationName  | Yes      | Application name in Casdoor                                  |

### Getting Configuration Parameters from Casdoor

1. **endpoint**: Your Casdoor server URL
2. **clientId** and **clientSecret**: Found in your application settings in Casdoor admin panel
3. **certificate**: Copy the certificate content from your application's "Cert" field (must be in x509 PEM format)
4. **organizationName**: The organization that owns your application
5. **applicationName**: Your application's name in Casdoor

### Initialize Configuration

```php
use Casdoor\Auth\User;

User::initConfig(
    'http://localhost:8000',
    'CLIENT_ID',
    'CLIENT_SECRET',
    file_get_contents('/path/to/certificate.pem'),
    'my-organization',
    'my-application'
);
```

## 🔐 Authentication

### OAuth 2.0 Flow

The SDK provides complete OAuth 2.0 authentication flow support.

#### Step 1: Redirect User to Casdoor Login

Generate the authorization URL:

```php
use Casdoor\Auth\Url;

$redirectUrl = 'https://your-app.com/callback';
$state = 'random-state-string';
$authUrl = Url::getSigninUrl($redirectUrl, $state);

// Redirect user to $authUrl
header('Location: ' . $authUrl);
```

#### Step 2: Handle OAuth Callback

After successful authentication, Casdoor redirects back to your application with `code` and `state` parameters:

```php
use Casdoor\Auth\Token;
use Casdoor\Auth\Jwt;

// Extract code and state from the callback URL
$code = $_GET['code'];
$state = $_GET['state'];

// Exchange code for access token
$token = new Token();
$oauthToken = $token->getOAuthToken($code, $state);
$accessToken = $oauthToken->getToken();

// Parse the JWT token to get user information
$jwt = new Jwt();
$claims = $jwt->parseJwtToken($accessToken, User::$authConfig);

// Access user information
echo "User: " . $claims['name'] . "\n";
echo "Email: " . $claims['email'] . "\n";
```

#### Step 3: Store User Session

After getting user information, store it in your session:

```php
session_start();
$_SESSION['user'] = $claims;
$_SESSION['access_token'] = $accessToken;
```

### Token Refresh

Refresh an expired access token using the refresh token:

```php
use Casdoor\Auth\Token;

$token = new Token();
$newToken = $token->refreshOAuthToken($refreshToken);
```

## 📦 Resource Management

The SDK provides comprehensive APIs to manage various resources in Casdoor.

### User Management

```php
use Casdoor\Auth\User;

// Get all users in your organization
$users = User::getUsers();

// Get a specific user by name
$user = User::getUser('username');

// Get user count
$count = User::getUserCount('1'); // '1' for online users, '0' for all users

// Create a new user
$user = new User();
$user->owner = 'my-organization';
$user->name = 'new-user';
$user->displayName = 'New User';
$user->email = 'newuser@example.com';
$user->phone = '+1234567890';
$user->password = 'password123';
$success = $user->addUser($user);

// Update an existing user
$user->displayName = 'Updated Name';
$success = $user->updateUser($user);

// Delete a user
$success = $user->deleteUser($user);
```

### Organization Management

```php
use Casdoor\Auth\Organization;

// Get all organizations
$orgs = Organization::getOrganizations();

// Get a specific organization
$org = Organization::getOrganization('org-name');

// Add a new organization
$org = new Organization();
$org->name = 'new-org';
$org->displayName = 'New Organization';
$success = Organization::addOrganization($org);

// Update an organization
$success = Organization::updateOrganization($org);

// Delete an organization
$success = Organization::deleteOrganization($org);
```

### Application Management

```php
use Casdoor\Auth\Application;

// Get all applications
$apps = Application::getApplications();

// Get a specific application
$app = Application::getApplication('app-name');

// Add, update, or delete applications
$success = Application::addApplication($app);
$success = Application::updateApplication($app);
$success = Application::deleteApplication($app);
```

### Email and SMS

```php
use Casdoor\Auth\Email;
use Casdoor\Auth\Sms;

// Send email
Email::sendEmail('Title', 'Content', 'sender@example.com', 'receiver@example.com');

// Send SMS
Sms::sendSms('randomCode', '+1234567890');
```

### Resource Management

Upload and manage resources (files, images, etc.):

```php
use Casdoor\Auth\Resource;

// Upload a resource
$resource = Resource::uploadResource(
    'user',
    'tag',
    'parent',
    'fullFilePath',
    $fileContent
);

// Delete a resource
$success = Resource::deleteResource($resource);
```

## 📚 API Reference

### Available Resources

The SDK provides comprehensive support for managing the following Casdoor resources:

| Resource      | Description                                   | Status           |
|---------------|-----------------------------------------------|------------------|
| **User**      | User accounts and profiles                    | ✅ Implemented    |
| **Organization** | Organization entities                      | ✅ Implemented    |
| **Application** | Application configurations                  | ✅ Implemented    |
| **Token**     | Access and refresh tokens                     | ✅ Implemented    |
| **Resource**  | File and media resources                      | ✅ Implemented    |
| **Email**     | Email sending                                 | ✅ Implemented    |
| **SMS**       | SMS sending                                   | ✅ Implemented    |
| **Role**      | User roles                                    | 🔄 In Progress    |
| **Permission** | Access permissions                           | 🔄 In Progress    |
| **Provider**  | Third-party authentication providers          | 🔄 In Progress    |
| **Certificate** | SSL/TLS certificates                        | 🔄 In Progress    |
| **Session**   | User sessions                                 | 🔄 In Progress    |
| **Webhook**   | Event webhooks                                | 🔄 In Progress    |
| **Group**     | User groups                                   | 🔄 In Progress    |
| **Syncer**    | User synchronization from external systems    | 🔄 In Progress    |
| **Adapter**   | Policy adapters                               | 🔄 In Progress    |
| **Enforcer**  | Policy enforcers                              | 🔄 In Progress    |
| **Model**     | Policy models                                 | 🔄 In Progress    |
| **Policy**    | Access control policies                       | 🔄 In Progress    |
| **Payment**   | Payment records                               | 🔄 In Progress    |
| **Product**   | Products/services                             | 🔄 In Progress    |
| **Subscription** | User subscriptions                         | 🔄 In Progress    |
| **Plan**      | Subscription plans                            | 🔄 In Progress    |
| **Pricing**   | Pricing configurations                        | 🔄 In Progress    |
| **Transaction** | Payment transactions                        | 🔄 In Progress    |
| **Invitation** | User invitations                             | 🔄 In Progress    |
| **Record**    | Audit and activity records                    | 🔄 In Progress    |

### Method Patterns

Most resources follow consistent method patterns:

- `get{Resource}s()` - Get all resources
- `get{Resource}($name)` - Get a specific resource
- `add{Resource}($resource)` - Create a new resource
- `update{Resource}($resource)` - Update an existing resource
- `delete{Resource}($resource)` - Delete a resource

## 💡 Examples

### Complete Authentication Example

```php
<?php

require_once 'vendor/autoload.php';

use Casdoor\Auth\User;
use Casdoor\Auth\Token;
use Casdoor\Auth\Jwt;
use Casdoor\Auth\Url;

// Initialize SDK
User::initConfig(
    'http://localhost:8000',
    'CLIENT_ID',
    'CLIENT_SECRET',
    file_get_contents('/path/to/certificate.pem'),
    'my-organization',
    'my-app'
);

// Step 1: Redirect to login (in your login page)
$redirectUrl = 'https://your-app.com/callback';
$state = bin2hex(random_bytes(16));
$_SESSION['oauth_state'] = $state;
$authUrl = Url::getSigninUrl($redirectUrl, $state);
header('Location: ' . $authUrl);
exit;

// Step 2: Handle callback (in your callback page)
session_start();
$code = $_GET['code'];
$state = $_GET['state'];

// Verify state
if ($state !== $_SESSION['oauth_state']) {
    die('Invalid state parameter');
}

// Exchange code for token
$token = new Token();
$oauthToken = $token->getOAuthToken($code, $state);
$accessToken = $oauthToken->getToken();

// Parse token to get user info
$jwt = new Jwt();
$user = $jwt->parseJwtToken($accessToken, User::$authConfig);

// Store in session
$_SESSION['user'] = $user;
$_SESSION['access_token'] = $accessToken;

echo "Welcome, " . $user['displayName'] . "!";
```

### User Management Example

```php
<?php

require_once 'vendor/autoload.php';

use Casdoor\Auth\User;

User::initConfig('http://localhost:8000', 'CLIENT_ID', 'CLIENT_SECRET', $cert, 'org', 'app');

// Create a new user
$newUser = new User();
$newUser->owner = 'my-organization';
$newUser->name = 'john_doe';
$newUser->displayName = 'John Doe';
$newUser->email = 'john@example.com';
$newUser->password = 'secure_password';

$success = $newUser->addUser($newUser);
if ($success) {
    echo "User created successfully!\n";
}

// Get and update user
$user = User::getUser('john_doe');
if ($user) {
    $userObj = new User();
    // Copy properties from array to object
    foreach ($user as $key => $value) {
        $userObj->$key = $value;
    }
    $userObj->displayName = 'John D.';
    $success = $userObj->updateUser($userObj);
    echo "User updated: " . ($success ? 'Yes' : 'No') . "\n";
}
```

## 📖 Documentation

For more detailed information, please refer to:

- [Casdoor Official Documentation](https://casdoor.org/docs/overview)
- [Casdoor GitHub Repository](https://github.com/casdoor/casdoor)
- [API Documentation](https://door.casdoor.com/swagger)
- [PHP SDK Repository](https://github.com/casdoor/casdoor-php-sdk)

## 🤝 Contributing

For casdoor, if you have any questions, you can give Issues, or you can also directly start Pull Requests (but we recommend giving issues first to communicate with the community).

Contributions are always welcome! Please ensure your code follows the existing code style and includes appropriate tests.

## 📄 License

This project is licensed under the [Apache License 2.0](LICENSE).

# How to contact?

- Discord: https://discord.gg/5rPsrAzK7S
- Contact: https://casdoor.org/help

# Contribute

For casdoor, if you have any questions, you can give Issues, or you can also directly start Pull Requests(but we recommend giving issues first to communicate with the community).

# License

[Apache-2.0](https://github.com/casdoor/casdoor-php-sdk/blob/master/LICENSE)

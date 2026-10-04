# Casdoor PHP SDK

<p align="center">
  <a href="#badge">
    <img alt="semantic-release" src="https://img.shields.io/badge/%20%20%F0%9F%93%A6%F0%9F%9A%80-semantic--release-e10079.svg">
  </a>
  <a href="https://github.com/casdoor/casdoor-php-sdk/actions/workflows/build.yml">
    <img alt="GitHub Workflow Status (branch)" src="https://img.shields.io/github/actions/workflow/status/casdoor/casdoor-php-sdk/build.yml?branch=master">
  </a>
  <a href="https://github.com/casdoor/casdoor-php-sdk/releases/latest">
    <img alt="GitHub Release" src="https://img.shields.io/github/v/release/casdoor/casdoor-php-sdk.svg">
  </a>
  <a href="https://packagist.org/packages/casdoor/casdoor-php-sdk">
    <img alt="Latest Stable Version" src="https://poser.pugx.org/casdoor/casdoor-php-sdk/v">
  </a>
  <a href="https://packagist.org/packages/casdoor/casdoor-php-sdk">
    <img alt="Total Downloads" src="https://poser.pugx.org/casdoor/casdoor-php-sdk/downloads">
  </a>
  <a href="https://packagist.org/packages/casdoor/casdoor-php-sdk">
    <img alt="PHP Version Require" src="https://poser.pugx.org/casdoor/casdoor-php-sdk/require/php">
  </a>
</p>

<p align="center">
  <a href="https://github.com/casdoor/casdoor-php-sdk/blob/master/LICENSE">
    <img src="https://img.shields.io/github/license/casdoor/casdoor-php-sdk?style=flat-square" alt="license">
  </a>
  <a href="https://github.com/casdoor/casdoor-php-sdk/issues">
    <img alt="GitHub issues" src="https://img.shields.io/github/issues/casdoor/casdoor-php-sdk?style=flat-square">
  </a>
  <a href="#">
    <img alt="GitHub stars" src="https://img.shields.io/github/stars/casdoor/casdoor-php-sdk?style=flat-square">
  </a>
  <a href="https://github.com/casdoor/casdoor-php-sdk/network">
    <img alt="GitHub forks" src="https://img.shields.io/github/forks/casdoor/casdoor-php-sdk?style=flat-square">
  </a>
  <a href="https://discord.gg/5rPsrAzK7S">
    <img alt="Casdoor" src="https://img.shields.io/discord/1022748306096537660?style=flat-square&logo=discord&label=discord&color=5865F2">
  </a>
</p>

Casdoor PHP SDK is the official PHP client library for [Casdoor](https://casdoor.ai/). It lets your PHP backend (Laravel, Symfony, plain PHP, ...) sign users in with Casdoor (OAuth 2.0 / OIDC), verify the JWT tokens issued by Casdoor, and manage users, organizations, applications, roles, permissions and all the other Casdoor objects through the Casdoor APIs.

The SDK has the same features as [casdoor-go-sdk](https://github.com/casdoor/casdoor-go-sdk).

## 📋 Table of Contents

- [Features](#-features)
- [Installation](#-installation)
- [Quick Start](#-quick-start)
- [Configuration](#️-configuration)
- [Authentication](#-authentication)
- [Resource Management](#-resource-management)
- [API Reference](#-api-reference)
- [Examples](#-examples)
- [Development](#-development)
- [Documentation](#-documentation)
- [License](#-license)

## ✨ Features

- **OAuth 2.0 Authentication**: authorization code, password and refresh token grants, token introspection, SSO logout
- **JWT Verification**: verify the tokens signed by Casdoor (RS256, RS512, ES256, ES384, ES512)
- **Calling APIs as the User**: `withAccessToken()` calls the APIs with the user's own permissions
- **User Management**: CRUD, lookup by email / phone / user ID, pagination, password check and change
- **Organization & Application Management**: organizations, applications, groups, certificates, providers, LDAP
- **Authorization**: roles, permissions, models, adapters, enforcers, policies, `enforce()` and `batchEnforce()`
- **Billing**: products, orders, payments, plans, pricings, subscriptions and transactions
- **Messaging**: send emails, SMS and notifications
- **Multi-Factor Authentication (MFA)**: TOTP, email and SMS MFA setup
- **Other Objects**: sessions, tokens, webhooks, syncers, invitations, resources (file upload) and records
- **PHP 8.0+**

## 📦 Installation

```bash
composer require casdoor/casdoor-php-sdk
```

## 🚀 Quick Start

```php
use Casdoor\Client;

$client = new Client(
    endpoint: 'http://localhost:8000',
    clientId: '<client-id>',
    clientSecret: '<client-secret>',
    certificate: file_get_contents('/path/to/cert.pem'),
    organizationName: 'my-organization',
    applicationName: 'my-application',
);

$users = $client->getUsers();
echo 'Found ' . count($users) . " users\n";
```

## ⚙️ Configuration

### Configuration Parameters

| Parameter        | Required | Description                                                                   |
|------------------|----------|-------------------------------------------------------------------------------|
| endpoint         | Yes      | Casdoor server URL, such as `http://localhost:8000`                           |
| clientId         | Yes      | Client ID of the Casdoor application                                          |
| clientSecret     | Yes      | Client secret of the Casdoor application                                      |
| certificate      | Yes      | x509 certificate (PEM) of the application's cert, used to verify JWT tokens   |
| organizationName | Yes      | Name of the Casdoor organization                                              |
| applicationName  | Yes      | Name of the Casdoor application                                               |
| customHeaders    | No       | HTTP headers added to all the API requests, e.g. `['Accept-Language' => 'de']` |

### Getting the Configuration from Casdoor

1. **endpoint**: the URL of your Casdoor server
2. **clientId** and **clientSecret**: the application's edit page in the Casdoor admin panel
3. **certificate**: the certificate of the cert selected in the application's "Cert" field (Certs page → the cert → "Certificate")
4. **organizationName**: the organization that owns your users
5. **applicationName**: the name of your application

### Custom HTTP Headers

```php
$client->setCustomHeaders([
    'Accept-Language' => 'de',     // localized error messages
    'X-Trace-ID'      => 'trace-abc-123',
]);
```

The `Authorization` header is always managed by the SDK.

### Return Values and Errors

- `getXxxs()` and `getXxx()` return the objects as associative arrays decoded from Casdoor's JSON, `getXxx()` returns `null` when the object doesn't exist
- `getPaginationXxxs()` returns `[$objects, $totalCount]`
- `addXxx()`, `updateXxx()` and `deleteXxx()` take the entity classes (`Casdoor\User`, `Casdoor\Role`, ...) and return `true` when the object is changed
- The methods throw `Casdoor\Exceptions\CasdoorException` with Casdoor's error message when Casdoor returns an error

## 🔐 Authentication

### OAuth 2.0 Flow

#### Step 1: Redirect the User to Casdoor

```php
header('Location: ' . $client->getSigninUrl('http://localhost:8080/callback'));
```

`getSignupUrl($enablePassword, $redirectUri)`, `getUserProfileUrl($userName, $accessToken)` and `getMyProfileUrl($accessToken)` build the URLs of the other Casdoor pages.

#### Step 2: Handle the Callback

Casdoor redirects back to your application with `code` and `state`. Exchange the code for the tokens and verify the access token:

```php
$token  = $client->getOAuthToken($_GET['code'], $_GET['state']);
$claims = $client->parseJwtToken($token->getToken());

echo $claims['name'], $claims['email'], $claims['owner'];

// e.g. save the user in the session
$_SESSION['user'] = $claims;
```

`getOAuthToken()` returns a [league/oauth2-client](https://oauth2-client.thephpleague.com/) `AccessToken`. `parseJwtToken()` verifies the signature and the expiration of the token with the certificate, and throws an exception when the token is invalid.

### Password Grant, Impersonation and Token Refresh

```php
// Resource Owner Password Credentials grant, the application must enable the "Password" grant type
$token = $client->getOAuthTokenByPassword('alice', 'password');

// Sign in as any user of the organization with the organization's master password
$token = $client->impersonateUser('alice', '<master password>');

$newToken = $client->refreshOAuthToken($token->getRefreshToken());

$result = $client->introspectToken($token->getToken(), 'access_token');
```

### Calling APIs With the User's Access Token

By default, the SDK calls the Casdoor APIs as the application itself: it authenticates with the client ID and client secret, so the calls have the application's (admin) permissions.

To call the APIs on behalf of the signed-in user instead, use `withAccessToken()` with the user's access token. It returns a new client that sends the `Authorization: Bearer <access_token>` header, so Casdoor treats the requests as being made by that user and the user's own permissions apply:

```php
$userClient = $client->withAccessToken($token->getToken());

// "Who am I"
$account = $userClient->getAccount();

// Any other API can be called in the same way
$users = $userClient->getUsers();
```

The original client is not changed, so it's safe to create one such client per incoming HTTP request.

**Note**: a non-admin user can only access their own data. If an API throws a permission error, the user simply isn't allowed to call it — use the application's client (without `withAccessToken()`) for admin operations.

### Logout

```php
$client->logout($accessToken);                // sign the user out of all the applications and devices (SSO logout)
$client->logoutCurrentSession($accessToken);  // only sign out the session of this access token
```

## 📦 Resource Management

### Object Owner

Every object in Casdoor is identified by an ID of the form `owner/name`, where the owner is an organization (`role`, `group`, `user`, `product`, `ldap`, ...) or the built-in `admin` owner (`organization`, `application`, `token`).

By default the SDK fills in the owner for you: the `organizationName` of the client, or `admin` for the object types listed above. You can address an object in another organization by passing a qualified `owner/name` ID instead of a plain name, and by setting the `owner` field explicitly when creating or updating an object:

```php
$client->getRole('my-role');            // "my-organization/my-role"
$client->getRole('other-org/my-role');  // "other-org/my-role"

$role        = new Casdoor\Role();
$role->owner = 'other-org';
$role->name  = 'my-role';
$client->addRole($role);                // created in "other-org"
```

> [!IMPORTANT]
> **Behavior change:** `addXxx()`, `updateXxx()` and `deleteXxx()` used to overwrite the `owner` field of the object with the client's organization, and to ignore any owner set by the caller. They now only fill `owner` in when it is empty. If your code sets `owner` to a value other than the client's organization (for example the literal `'admin'`), the request is now sent to that owner instead of being silently redirected, so clear the field or set it to the intended organization.

### Method Patterns

Most objects have the same methods:

- `getXxxs()` - get all the objects of the organization
- `getPaginationXxxs($p, $pageSize, $queryMap = [])` - get a page of the objects, returns `[$objects, $total]`. `$queryMap` can filter and sort, e.g. `['field' => 'name', 'value' => 'abc', 'sortField' => 'createdTime', 'sortOrder' => 'descend']`
- `getXxx($name)` - get an object by name (or `owner/name` ID)
- `addXxx($object)` - create an object
- `updateXxx($object)` - update an object
- `updateXxxForColumns($object, $columns)` - only update the given columns (users, roles, permissions, sessions, tokens, invitations)
- `deleteXxx($object)` - delete an object

### Users

```php
use Casdoor\User;

$client->getUsers();
[$users, $total] = $client->getPaginationUsers(1, 10);
$client->getUser('alice');
$client->getUserByEmail('alice@example.com');
$client->getUserByPhone('2025550123');
$client->getUserByUserId('<user id>');
$client->getSortedUsers('created_time', 10);
$client->getGlobalUsers();     // users of all organizations
$client->getUserCount('1');    // '1' for online users, '0' for offline users, '' for all users

$user              = new User();
$user->name        = 'alice';
$user->displayName = 'Alice';
$user->password    = '123456';
$client->addUser($user);
$client->updateUser($user);
$client->updateUserForColumns($user, ['displayName', 'email']);
$client->updateUserById('my-organization/alice', $user);
$client->updateUserByUserId('my-organization', '<user id>', $user);
$client->deleteUser($user);

$client->checkUserPassword($user);  // true or false
$client->setPassword('my-organization', 'alice', '123456', '654321');
```

### Permissions and Enforcement

```php
$client->getPermissionsByRole('admin');

// Check a request against a permission (or a model / resource / enforcer / owner)
$allowed = $client->enforce('my-organization/read-data', '', '', '', '', ['my-organization/alice', 'data1', 'read']);
$results = $client->batchEnforce('my-organization/read-data', '', '', '', '', [
    ['my-organization/alice', 'data1', 'read'],
    ['my-organization/bob', 'data1', 'read'],
]);
```

### Enforcers and Policies

```php
use Casdoor\CasbinRule;

$client->getPolicies('my-enforcer', '');
$client->getFilteredPolicies('my-organization/my-enforcer', [['ptype' => 'p', 'fieldIndex' => 0, 'fieldValues' => ['alice']]]);
$client->addPolicy($enforcer, CasbinRule::new('p', 'alice', 'data1', 'read'));
$client->updatePolicy($enforcer, $oldPolicy, $newPolicy);
$client->removePolicy($enforcer, $policy);
```

### Billing: Products, Orders, Payments and Transactions

```php
// Place an order of products for a user and pay it with a payment provider
$order   = $client->placeOrder([['name' => 'my-product', 'quantity' => 1]], 'alice');
$payment = $client->payOrder($order['name'], 'my-payment-provider');
$client->cancelOrder($order['name']);

$client->getUserOrders('alice');
$client->getUserPayments('alice');
$client->getUserTransactions('alice');

// Validate a transaction (e.g. the balance) without saving it
$client->addTransactionWithDryRun($transaction, true);
[$affected, $name] = $client->addTransaction($transaction);
```

### Email, SMS and Notifications

```php
$client->sendEmail('Hello', 'Hello world', 'Casdoor', ['alice@example.com']);
$client->sendEmailByProvider('Hello', 'Hello world', 'Casdoor', 'my-email-provider', ['alice@example.com']);
$client->sendSms('123456', ['+12025550123']);
$client->sendSmsByProvider('123456', 'my-sms-provider', ['+12025550123']);
$client->sendNotification('Hello', 'alice');
```

### Resources (File Upload)

```php
[$fileUrl, $name] = $client->uploadResource('alice', 'avatar', 'user', '/avatar/alice.png', file_get_contents('avatar.png'));

$client->getResources('my-organization', 'alice', '', '', '', '');
$client->deleteResourceWithTag($resource, 'Direct');
```

### LDAP

```php
$client->getLdaps();
$client->getLdapUsers('<ldap id>');
$client->syncLdapUsersFromServer('<ldap id>');  // fetch and sync all the LDAP users
```

### Multi-Factor Authentication

```php
$setup = $client->initiate('my-organization', 'app', 'alice')['data'];
$client->verify('my-organization', 'app', 'alice', $setup['secret'], '<passcode>');
$client->enable('my-organization', 'app', 'alice', $setup['secret'], $setup['recoveryCodes'][0]);
$client->setPreferred('my-organization', 'app', 'alice');
$client->delete('my-organization', 'alice');
```

## 📚 API Reference

| Object           | Methods                                                                                                                         |
|------------------|---------------------------------------------------------------------------------------------------------------------------------|
| **Auth**         | `getOAuthToken`, `getOAuthTokenByPassword`, `impersonateUser`, `refreshOAuthToken`, `introspectToken`, `parseJwtToken`, `logout`, `logoutCurrentSession`, `withAccessToken`, `getAccount` |
| **URL**          | `getSigninUrl`, `getSignupUrl`, `getUserProfileUrl`, `getMyProfileUrl`                                                          |
| **User**         | CRUD + pagination, `getUserByEmail`, `getUserByPhone`, `getUserByUserId`, `getSortedUsers`, `getGlobalUsers`, `getUserCount`, `updateUserForColumns`, `updateUserById`, `updateUserByUserId`, `checkUserPassword`, `setPassword` |
| **Organization** | CRUD, `getOrganizationNames`                                                                                                    |
| **Application**  | CRUD, `getOrganizationApplications`                                                                                             |
| **Group**        | CRUD + pagination                                                                                                               |
| **Cert**         | CRUD, `getGlobalCerts`                                                                                                          |
| **Provider**     | CRUD + pagination                                                                                                               |
| **Role**         | CRUD + pagination, `updateRoleForColumns`                                                                                       |
| **Permission**   | CRUD + pagination, `updatePermissionForColumns`, `getPermissionsByRole`                                                         |
| **Model / Adapter / Enforcer** | CRUD + pagination                                                                                                 |
| **Policy**       | `getPolicies`, `getFilteredPolicies`, `addPolicy`, `updatePolicy`, `removePolicy`                                               |
| **Enforce**      | `enforce`, `batchEnforce`                                                                                                       |
| **Session**      | CRUD + pagination, `updateSessionForColumns`                                                                                    |
| **Token**        | CRUD + pagination, `updateTokenForColumns`, `introspectToken`                                                                   |
| **Product**      | CRUD + pagination                                                                                                               |
| **Order**        | CRUD + pagination, `getUserOrders`, `placeOrder`, `payOrder`, `buyProduct`, `cancelOrder`                                       |
| **Payment**      | CRUD + pagination, `getUserPayments`, `notifyPayment`, `invoicePayment`                                                         |
| **Plan / Pricing / Subscription** | CRUD + pagination                                                                                              |
| **Transaction**  | CRUD + pagination, `getUserTransactions`, `addTransactionWithDryRun`                                                            |
| **Invitation**   | CRUD + pagination, `updateInvitationForColumns`, `getInvitationInfo`                                                            |
| **LDAP**         | CRUD, `getLdapUsers`, `syncLdapUsers`, `syncLdapUsersFromServer`                                                                |
| **Syncer / Webhook** | CRUD + pagination                                                                                                           |
| **Resource**     | `getResources`, `getPaginationResources`, `getResource`, `getResourceEx`, `addResource`, `updateResource`, `uploadResource`, `uploadResourceEx`, `deleteResource`, `deleteResourceWithTag` |
| **Record**       | `getRecords`, `getPaginationRecords`, `getRecord`, `addRecord`                                                                  |
| **Email / SMS / Notification** | `sendEmail`, `sendEmailByProvider`, `sendSms`, `sendSmsByProvider`, `sendNotification`                            |
| **MFA**          | `initiate`, `verify`, `enable`, `setPreferred`, `delete`                                                                        |
| **Low-level**    | `getUrl`, `doGetResponse`, `doGetBytes`, `doGetBytesRaw`, `doPost`, `doPostBytesRaw` to call any other Casdoor API              |

## 💡 Examples

- Laravel: https://github.com/casdoor/casdoor-php-laravel-example

## 🛠 Development

The tests run against a real Casdoor server. CI starts one with Docker and the data in [.ci/casdoor/init_data.json](.ci/casdoor/init_data.json):

```bash
docker run -d --name casdoor -p 8000:8000 \
  -e driverName=sqlite \
  -e dataSourceName='file:casdoor.db?cache=shared' \
  -e initDataFile=/init_data.json \
  -v "$PWD/.ci/casdoor/init_data.json:/init_data.json:ro" \
  casbin/casdoor-all-in-one

composer install
vendor/bin/phpunit --testdox
```

Set `CASDOOR_TEST_ENDPOINT`, `CASDOOR_TEST_CLIENT_ID`, `CASDOOR_TEST_CLIENT_SECRET`, `CASDOOR_TEST_ORGANIZATION` and `CASDOOR_TEST_APPLICATION` to run the tests against another server.

Releases are tagged automatically by semantic-release when commits are pushed to `master`, and Packagist picks up the tags.

## 📖 Documentation

- [Casdoor Documentation](https://casdoor.ai/docs/overview)
- [Casdoor PHP SDK Documentation](https://casdoor.ai/docs/how-to-connect/sdk)
- [Casdoor API Documentation](https://door.casdoor.com/swagger)
- [Casdoor GitHub Repository](https://github.com/casdoor/casdoor)

## 📄 License

This project is licensed under the Apache License 2.0 - see the [LICENSE](LICENSE) file for details.

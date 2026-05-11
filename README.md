# casdoor-php-sdk

[![Latest Stable Version](http://poser.pugx.org/casdoor/casdoor-php-sdk/v)](https://packagist.org/packages/casdoor/casdoor-php-sdk)
[![Total Downloads](http://poser.pugx.org/casdoor/casdoor-php-sdk/downloads)](https://packagist.org/packages/casdoor/casdoor-php-sdk)
[![License](http://poser.pugx.org/casdoor/casdoor-php-sdk/license)](https://packagist.org/packages/casdoor/casdoor-php-sdk)
[![PHP Version Require](http://poser.pugx.org/casdoor/casdoor-php-sdk/require/php)](https://packagist.org/packages/casdoor/casdoor-php-sdk)

PHP client SDK for [Casdoor](https://casdoor.org/).

## Installation

```bash
composer require casdoor/casdoor-php-sdk
```

## Quick Start

Initialize the client:

```php
use Casdoor\Client;

$client = new Client(
    endpoint: 'https://door.casdoor.com',
    clientId: 'YOUR_CLIENT_ID',
    clientSecret: 'YOUR_CLIENT_SECRET',
    certificate: file_get_contents('/path/to/public_key.pem'),
    organizationName: 'built-in',
    applicationName: 'app-built-in'
);
```

## Auth / OAuth

```php
// Get OAuth sign-in URL
$signinUrl = $client->getSigninUrl('https://your-app.com/callback');

// Exchange authorization code for access token
$token = $client->getOAuthToken($code, $state);

// Parse JWT token
$claims = $client->parseJwtToken($token->getToken());
```

## User Management

```php
use Casdoor\User;

// Get all users
$users = $client->getUsers();

// Get single user
$user = $client->getUser('alice');

// Create user
$user = new User();
$user->name = 'alice';
$user->email = 'alice@example.com';
$client->addUser($user);

// Update user
$user->displayName = 'Alice';
$client->updateUser($user);

// Delete user
$client->deleteUser($user);

// Paginate users
[$users, $total] = $client->getPaginationUsers(1, 10);
```

## Role & Permission Management

```php
use Casdoor\Role;
use Casdoor\Permission;

$roles = $client->getRoles();
$permissions = $client->getPermissions();

$role = new Role();
$role->name = 'admin';
$client->addRole($role);
```

## Other Resources

The SDK supports the full Casdoor API. Available methods follow the same patterns:

| Resource     | Methods |
|---|---|
| Organization | getOrganization, getOrganizations, addOrganization, updateOrganization, deleteOrganization |
| Application  | getApplications, getApplication, addApplication, updateApplication, deleteApplication |
| Group        | getGroups, getGroup, addGroup, updateGroup, deleteGroup |
| Cert         | getCerts, getCert, addCert, updateCert, deleteCert |
| Provider     | getProviders, getProvider, addProvider, updateProvider, deleteProvider |
| Resource     | getResources, getResource, uploadResource, deleteResource |
| Webhook      | getWebhooks, getWebhook, addWebhook, updateWebhook, deleteWebhook |
| Session      | getSessions, getSession, addSession, updateSession, deleteSession |
| Syncer       | getSyncers, getSyncer, addSyncer, updateSyncer, deleteSyncer |
| Plan         | getPlans, getPlan, addPlan, updatePlan, deletePlan |
| Pricing      | getPricings, getPricing, addPricing, updatePricing, deletePricing |
| Subscription | getSubscriptions, getSubscription, addSubscription, updateSubscription, deleteSubscription |
| Product      | getProducts, getProduct, addProduct, updateProduct, deleteProduct |
| Order        | getOrders, getOrder, addOrder, updateOrder, deleteOrder, cancelOrder |
| Payment      | getPayments, getPayment, addPayment, updatePayment, deletePayment |
| Transaction  | getTransactions, getTransaction, addTransaction, updateTransaction, deleteTransaction |
| Invitation   | getInvitations, getInvitation, addInvitation, updateInvitation, deleteInvitation |
| Adapter      | getAdapters, getAdapter, addAdapter, updateAdapter, deleteAdapter |
| Enforcer     | getEnforcers, getEnforcer, addEnforcer, updateEnforcer, deleteEnforcer |
| Model        | getModels, getModel, addModel, updateModel, deleteModel |
| Policy       | getPolicies, addPolicy, updatePolicy, removePolicy |
| Email        | sendEmail, sendEmailByProvider |
| SMS          | sendSms, sendSmsByProvider |
| LDAP         | getLdaps, getLdap, addLdap, updateLdap, deleteLdap, getLdapUsers, syncLdapUsers |

## License

[Apache 2.0](LICENSE)

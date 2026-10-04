<?php
// Copyright 2026 The Casdoor Authors. All Rights Reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

declare(strict_types=1);

namespace Casdoor\Tests;

use Casdoor\Exceptions\CasdoorException;

/**
 * Adds, gets, updates and deletes each type of object, the same as the tests of casdoor-go-sdk.
 */
class CrudTest extends TestBase
{
    public static function objectProvider(): array
    {
        $org = getenv('CASDOOR_TEST_ORGANIZATION') ?: 'casbin';
        return [
            'adapter'      => ['Adapter', ['owner' => $org, 'user' => 'adapter', 'host' => 'https://casdoor.org'], 'user'],
            'application'  => ['Application', ['owner' => 'admin', 'displayName' => 'app', 'logo' => 'https://cdn.casbin.org/img/casdoor-logo_1185x256.png', 'homepageUrl' => 'https://casdoor.org', 'description' => 'Casdoor Website', 'organization' => $org], 'description'],
            'cert'         => ['Cert', ['owner' => $org, 'displayName' => 'cert', 'scope' => 'JWT', 'type' => 'x509', 'cryptoAlgorithm' => 'RS256', 'bitSize' => 4096, 'expireInYears' => 20], 'displayName'],
            'enforcer'     => ['Enforcer', ['owner' => $org, 'displayName' => 'enforcer', 'model' => 'built-in/user-model-built-in', 'adapter' => 'built-in/user-adapter-built-in', 'description' => 'Casdoor Website'], 'description'],
            'group'        => ['Group', ['owner' => $org, 'displayName' => 'group'], 'displayName'],
            'model'        => ['Model', ['owner' => $org, 'displayName' => 'model', 'modelText' => "[request_definition]\nr = sub, obj, act\n\n[policy_definition]\np = sub, obj, act\n\n[policy_effect]\ne = some(where (p.eft == allow))\n\n[matchers]\nm = r.sub == p.sub && r.obj == p.obj && r.act == p.act"], 'displayName'],
            'organization' => ['Organization', ['owner' => 'admin', 'displayName' => 'organization', 'websiteUrl' => 'https://example.com', 'passwordType' => 'plain', 'passwordOptions' => ['AtLeast6'], 'countryCodes' => ['US'], 'languages' => ['en'], 'initScore' => 2000], 'displayName'],
            'payment'      => ['Payment', ['owner' => $org, 'displayName' => 'payment', 'products' => ['casdoor'], 'price' => 10.0, 'currency' => 'USD'], 'displayName'],
            'permission'   => ['Permission', ['owner' => $org, 'displayName' => 'permission', 'description' => 'Casdoor Website', 'users' => [$org . '/*'], 'model' => 'admin/user-model-built-in', 'resourceType' => 'Application', 'resources' => ['app-casbin'], 'actions' => ['Read', 'Write'], 'effect' => 'Allow', 'isEnabled' => true], 'description'],
            'plan'         => ['Plan', ['owner' => $org, 'displayName' => 'plan', 'description' => 'Casdoor Website', 'currency' => 'USD'], 'description'],
            'pricing'      => ['Pricing', ['owner' => $org, 'displayName' => 'pricing', 'application' => 'app-admin', 'description' => 'Casdoor Website'], 'description'],
            'product'      => ['Product', ['owner' => $org, 'displayName' => 'product', 'image' => 'https://cdn.casbin.org/img/casdoor-logo_1185x256.png', 'description' => 'Casdoor Website', 'tag' => 'auto_created_product_for_plan', 'quantity' => 999, 'state' => 'Published', 'providers' => ['provider_payment_dummy'], 'currency' => 'USD'], 'description'],
            'provider'     => ['Provider', ['owner' => $org, 'displayName' => 'provider', 'category' => 'Captcha', 'type' => 'Default'], 'displayName'],
            'role'         => ['Role', ['owner' => $org, 'displayName' => 'role', 'description' => 'Casdoor Website'], 'description'],
            'subscription' => ['Subscription', ['owner' => $org, 'displayName' => 'subscription', 'description' => 'Casdoor Website'], 'description'],
            'syncer'       => ['Syncer', ['owner' => $org, 'organization' => $org, 'host' => 'localhost', 'port' => 3306, 'user' => 'root', 'password' => '123', 'databaseType' => 'mysql', 'database' => 'syncer_db', 'table' => 'user_table', 'syncInterval' => 1], 'host'],
            'user'         => ['User', ['owner' => $org, 'displayName' => 'user'], 'displayName'],
            'webhook'      => ['Webhook', ['owner' => $org, 'organization' => $org], 'url'],
        ];
    }

    /**
     * @dataProvider objectProvider
     */
    public function testCrud(string $type, array $props, string $updateField): void
    {
        $class        = 'Casdoor\\' . $type;
        $name         = strtolower($type) . '_' . bin2hex(random_bytes(4));
        $object       = new $class();
        $object->name = $name;
        $object->createdTime = date('c');
        foreach ($props as $key => $value) {
            $object->$key = $value;
        }
        $id = $object->owner . '/' . $name;

        $this->assertTrue($this->client->{'add' . $type}($object), 'Failed to add object');

        $objects = $this->client->{'get' . $type . 's'}();
        $this->assertContains($name, array_column($objects, 'name'), 'Added object not found in list');

        $retrieved = $this->client->{'get' . $type}($id);
        $this->assertSame($name, $retrieved['name']);

        if (property_exists($object, 'id') && isset($retrieved['id']) && is_string($retrieved['id'])) {
            // the user ID is generated by Casdoor and can't be changed
            $object->id = $retrieved['id'];
        }
        $updated              = $updateField === 'url' ? 'https://example.com/webhook' : 'Updated Casdoor Website';
        $object->$updateField = $updated;
        // update-model returns no "Affected" even when it succeeds, so check the updated field instead
        $this->client->{'update' . $type}($object);
        $this->assertSame($updated, $this->client->{'get' . $type}($id)[$updateField]);

        $this->assertTrue($this->client->{'delete' . $type}($object), 'Failed to delete object');
        $this->assertNull($this->client->{'get' . $type}($id));
    }

    public function testPagination(): void
    {
        foreach (['Adapters', 'Enforcers', 'Groups', 'Invitations', 'Models', 'Orders', 'Payments', 'Permissions', 'Plans', 'Pricings', 'Products', 'Providers', 'Roles', 'Sessions', 'Subscriptions', 'Syncers', 'Tokens', 'Transactions', 'Users', 'Webhooks'] as $plural) {
            [$objects, $total] = $this->client->{'getPagination' . $plural}(1, 10);
            $this->assertIsArray($objects, $plural);
            $this->assertIsInt($total, $plural);
        }
    }

    public function testSession(): void
    {
        $session              = new \Casdoor\Session();
        $session->owner       = $this->client->organizationName;
        $session->name        = 'session_' . bin2hex(random_bytes(4));
        $session->createdTime = date('c');
        $session->application = 'app-built-in';
        $this->assertTrue($this->client->addSession($session));

        $retrieved = $this->client->getSession($session->name, $session->application);
        $this->assertSame($session->name, $retrieved['name']);

        $this->assertTrue($this->client->deleteSession($session));
    }

    public function testToken(): void
    {
        $token               = new \Casdoor\Token();
        $token->owner        = 'admin';
        $token->name         = 'token_' . bin2hex(random_bytes(4));
        $token->createdTime  = date('c');
        $token->application  = 'app-casbin';
        $token->organization = $this->client->organizationName;
        $token->user         = 'admin';
        $token->code         = 'abc';
        $token->accessToken  = '123456';
        $token->expiresIn    = 3600;
        $token->scope        = 'read';
        $token->tokenType    = 'Bearer';
        $this->assertTrue($this->client->addToken($token));
        $this->assertContains($token->name, array_column($this->client->getTokens(), 'name'));

        $token->code = 'Updated Code';
        $this->assertTrue($this->client->updateToken($token));
        $token->scope = 'profile';
        $this->assertTrue($this->client->updateTokenForColumns($token, ['scope']));
        $updated = $this->client->getToken($token->name);
        $this->assertSame('Updated Code', $updated['code']);
        $this->assertSame('profile', $updated['scope']);

        $this->assertTrue($this->client->deleteToken($token));
        $this->expectException(CasdoorException::class);
        $this->client->getToken($token->name);
    }

    public function testInvitation(): void
    {
        $code                     = 'TEST' . random_int(100000, 999999);
        $invitation               = new \Casdoor\Invitation();
        $invitation->owner        = $this->client->organizationName;
        $invitation->name         = 'invitation_' . bin2hex(random_bytes(4));
        $invitation->createdTime  = date('c');
        $invitation->displayName  = 'Test Invitation';
        $invitation->code         = $code;
        $invitation->defaultCode  = $code;
        $invitation->quota        = 10;
        $invitation->application  = 'app-casbin';
        $invitation->email        = 'test@example.com';
        $invitation->state        = 'Active';
        $this->assertTrue($this->client->addInvitation($invitation));

        $invitation->displayName = 'Updated Invitation';
        $this->assertTrue($this->client->updateInvitationForColumns($invitation, ['display_name']));
        $this->assertSame('Updated Invitation', $this->client->getInvitation($invitation->name)['displayName']);

        $info = $this->client->getInvitationInfo($code, 'app-casbin');
        $this->assertSame($invitation->name, $info['name']);

        $this->assertTrue($this->client->deleteInvitation($invitation));
        $this->assertNull($this->client->getInvitation($invitation->name));
    }

    public function testTransaction(): void
    {
        // a recharge of the organization doesn't need a user balance, so it can be added in CI
        $transaction              = new \Casdoor\Transaction();
        $transaction->owner       = $this->client->organizationName;
        $transaction->createdTime = date('c');
        $transaction->application = 'app-casbin';
        $transaction->domain      = 'https://casdoor.ai';
        $transaction->category    = 'Recharge';
        $transaction->type        = 'Recharge';
        $transaction->tag         = 'Organization';
        $transaction->amount      = 100.0;
        $transaction->currency    = 'USD';
        $transaction->state       = 'Paid';

        [, $dryRunName] = $this->client->addTransactionWithDryRun(clone $transaction, true);
        $this->assertIsString($dryRunName);

        [$affected, $name] = $this->client->addTransaction($transaction);
        $this->assertNotEmpty($name);
        $this->assertContains($name, array_column($this->client->getTransactions(), 'name'));

        $transaction->name        = $name;
        $transaction->displayName = 'Updated Transaction';
        $this->assertTrue($this->client->updateTransaction($transaction));
        $this->assertSame('Updated Transaction', $this->client->getTransaction($name)['displayName']);

        $this->assertTrue($this->client->deleteTransaction($transaction));
        $this->assertNull($this->client->getTransaction($name));
    }
}

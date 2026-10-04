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

use Casdoor\Adapter;
use Casdoor\CasbinRule;
use Casdoor\Enforcer;
use Casdoor\Model;
use Casdoor\Permission;

class EnforceTest extends TestBase
{
    public function testEnforce(): void
    {
        $org  = $this->client->organizationName;
        $name = 'permission_' . bin2hex(random_bytes(4));

        $permission               = new Permission();
        $permission->owner        = $org;
        $permission->name         = $name;
        $permission->createdTime  = date('c');
        $permission->users        = [$org . '/alice'];
        $permission->model        = 'built-in/user-model-built-in';
        $permission->resourceType = 'Application';
        $permission->resources    = ['data1'];
        $permission->actions      = ['read'];
        $permission->effect       = 'Allow';
        $permission->isEnabled    = true;
        $this->assertTrue($this->client->addPermission($permission));

        $this->assertTrue($this->client->enforce($org . '/' . $name, '', '', '', '', [$org . '/alice', 'data1', 'read']));
        $this->assertFalse($this->client->enforce($org . '/' . $name, '', '', '', '', [$org . '/bob', 'data1', 'read']));

        $results = $this->client->batchEnforce($org . '/' . $name, '', '', '', '', [
            [$org . '/alice', 'data1', 'read'],
            [$org . '/bob', 'data1', 'read'],
        ]);
        $this->assertSame([[true, false]], $results);

        $this->client->deletePermission($permission);
    }

    public function testPolicy(): void
    {
        $org  = $this->client->organizationName;
        $name = 'policy_' . bin2hex(random_bytes(4));

        $model              = new Model();
        $model->owner       = $org;
        $model->name        = $name;
        $model->createdTime = date('c');
        $model->modelText   = "[request_definition]\nr = sub, obj, act\n\n[policy_definition]\np = sub, obj, act\n\n[policy_effect]\ne = some(where (p.eft == allow))\n\n[matchers]\nm = r.sub == p.sub && r.obj == p.obj && r.act == p.act";
        $this->assertTrue($this->client->addModel($model));

        $adapter              = new Adapter();
        $adapter->owner       = $org;
        $adapter->name        = $name;
        $adapter->createdTime = date('c');
        $adapter->table       = 'casbin_rule_' . bin2hex(random_bytes(3));
        $adapter->useSameDb   = true;
        $this->assertTrue($this->client->addAdapter($adapter));

        $enforcer              = new Enforcer();
        $enforcer->owner       = $org;
        $enforcer->name        = $name;
        $enforcer->createdTime = date('c');
        $enforcer->model       = $org . '/' . $name;
        $enforcer->adapter     = $org . '/' . $name;
        $this->assertTrue($this->client->addEnforcer($enforcer));

        $policy        = new CasbinRule();
        $policy->ptype = 'p';
        $policy->v0    = 'alice';
        $policy->v1    = 'data1';
        $policy->v2    = 'read';
        $this->assertTrue($this->client->addPolicy($enforcer, $policy));

        $policies = $this->client->getPolicies($name, '');
        $this->assertContains('alice', array_column($policies, 'V0'));

        $filtered = $this->client->getFilteredPolicies($org . '/' . $name, [['ptype' => 'p', 'fieldIndex' => 0, 'fieldValues' => ['alice']]]);
        $this->assertCount(1, $filtered);

        $newPolicy = CasbinRule::new('p', 'alice', 'data1', 'write');
        $this->assertTrue($this->client->updatePolicy($enforcer, $policy, $newPolicy));
        $this->assertContains('write', array_column($this->client->getPolicies($name, ''), 'V2'));

        $this->assertTrue($this->client->removePolicy($enforcer, $newPolicy));
        $this->assertNotContains('write', array_column($this->client->getPolicies($name, ''), 'V2'));

        $this->client->deleteEnforcer($enforcer);
        $this->client->deleteAdapter($adapter);
        $this->client->deleteModel($model);
    }
}

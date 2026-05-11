<?php

// Copyright 2024 The Casdoor Authors. All Rights Reserved.
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

namespace Casdoor;

class Client extends CasdoorClient
{
    use AuthTrait;
    use JwtTrait;
    use UserTrait;
    use OrganizationTrait;
    use ApplicationTrait;
    use TokenTrait;
    use RoleTrait;
    use PermissionTrait;
    use GroupTrait;
    use CertTrait;
    use ProviderTrait;
    use ResourceTrait;
    use WebhookTrait;
    use SessionTrait;
    use SyncerTrait;
    use PlanTrait;
    use PricingTrait;
    use SubscriptionTrait;
    use ProductTrait;
    use OrderTrait;
    use PaymentTrait;
    use TransactionTrait;
    use InvitationTrait;
    use AdapterTrait;
    use EnforcerTrait;
    use ModelTrait;
    use PolicyTrait;
    use EmailTrait;
    use SmsTrait;
    use LdapTrait;
}

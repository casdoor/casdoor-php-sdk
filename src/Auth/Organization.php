<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

class Organization
{
    public string $owner;
    public string $name;
    public string $createdTime;

    public string $displayName;
    public string $websiteUrl;
    public string $favicon;
    public string $passwordType;
    public string $passwordSalt;
    public string $phonePrefix;
    public string $defaultAvatar;
    public string $masterPassword;
    public bool   $enableSoftDeletion;

    public function __construct(string $owner, string $name)
    {
        $this->owner = $owner;
        $this->name  = $name;
    }

    public function addOrganization(Organization $organization, AuthConfig $authConfig) :bool
    {
        if ($organization->owner == '') {
            $organization->owner = 'admin';
        }
        $postBytes = json_encode($organization, JSON_THROW_ON_ERROR);
    
        $resp = Util::doPost('add-organization', [], $authConfig, $postBytes, false);
    
        return $resp->data == 'Affected';
    }

    public function deleteOrganization(string $name, AuthConfig $authConfig): bool
    {
        $organization = new Organization('admin', $name);
        $postBytes = json_encode($organization, JSON_THROW_ON_ERROR);

        $resp = Util::doPost('delete-organization', [], $authConfig, $postBytes, false);

        return $resp->data == 'Affected';
    }
}

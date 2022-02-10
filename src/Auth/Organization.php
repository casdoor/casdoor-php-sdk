<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

/**
 * Class Organization is used to add or delete your casdoor organization
 */
class Organization
{
    /**
     * @var string
     */
    public $owner;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $createdTime;

    /**
     * @var string
     */
    public $displayName;

    /**
     * @var string
     */
    public $websiteUrl;

    /**
     * @var string
     */
    public $favicon;

    /**
     * @var string
     */
    public $passwordType;

    /**
     * @var string
     */
    public $passwordSalt;

    /**
     * @var string
     */
    public $phonePrefix;

    /**
     * @var string
     */
    public $defaultAvatar;

    /**
     * @var string
     */
    public $masterPassword;

    /**
     * @var bool
     */
    public $enableSoftDeletion;

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

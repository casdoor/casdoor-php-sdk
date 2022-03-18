<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

/**
 * Class Resource is used to upload or delete resources
 */
class Resource
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
     * @var AuthConfig
     */
    protected $authConfig;

    public function __construct(string $owner, string $name, AuthConfig $authConfig)
    {
        $this->owner      = $owner;
        $this->name       = $name;
        $this->authConfig = $authConfig;
    }

    public function uploadResource(string $tag, string $parent, string $fullFilePath, array $fileBytes): array
    {
        $queryMap = [
            'owner'        => 'admin',
            'application'  => $this->authConfig->applicationName,
            'tag'          => $tag,
            'parent'       => $parent,
            'fullFilePath' => $fullFilePath,
        ];
    
        $resp = Util::doPost('upload-resource', $queryMap, $this->authConfig, $fileBytes, true);

        if ($resp->status != 'ok') {
            return ['', ''];
        }

        $fileUrl = (string)$resp->data;
        $name = (string)$resp->data2;
        return [$fileUrl, $name];
    }
    
    public function uploadResourceEx(string $user, string $tag, string $parent, string $fullFilePath, array $fileBytes, string $createdTime, string $description): array
    {
        $queryMap = [
            'owner'        => $this->authConfig->organizationName,
            'user'         => $user,
            'application'  => $this->authConfig->applicationName,
            'tag'          => $tag,
            'parent'       => $parent,
            'fullFilePath' => $fullFilePath,
            'createdTime'  => $createdTime,
            'description'  => $description,
        ];
    
        $resp = Util::doPost('upload-resource', $queryMap, $this->authConfig, $fileBytes, true);

        if ($resp->status != 'ok') {
            return ['', ''];
        }
    
        $fileUrl = (string)$resp->data;
        $name = (string)$resp->data2;
        return [$fileUrl, $name];
    }

    public function deleteResource(): bool
    {
        $postBytes = json_encode($this);
        if ($postBytes === false) {
            return false;
        }
    
        $resp = Util::doPost('delete-resource', [], $this->authConfig, $postBytes, false);
    
        return $resp->data == 'Affected';
    }
}

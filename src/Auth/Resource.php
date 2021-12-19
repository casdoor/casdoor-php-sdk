<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

class Resource
{
    public string $owner;
    public string $name;
    protected AuthConfig $authConfig;

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

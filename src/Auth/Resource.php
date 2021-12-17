<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

class Resource
{
    public $owner;
    public $name;

    public function __construct($owner, $name)
    {
        $this->owner = $owner;
        $this->name  = $name;
    }

    public static function uploadResource(string $tag, string $parent, string $fullFilePath, array $fileBytes, AuthConfig $authConfig): array
    {
        $queryMap = [
            'owner'        => $authConfig->organizationName,
            'tag'          => $tag,
            'parent'       => $parent,
            'fullFilePath' => $fullFilePath,
        ];
    
        $resp = Util::doPost('upload-resource', $queryMap, $authConfig, $fileBytes);

        $fileUrl = (string)$resp->data;
        $name = (string)$resp->data2;
        return [$fileUrl, $name];
    }
    
    public static function deleteResource(string $name, AuthConfig $authConfig): bool
    {
        $resource = new self($authConfig->organizationName, $name);

        $postBytes = json_encode($resource);
        if ($postBytes === false) {
            return false;
        }
    
        $resp = Util::doPost('delete-resource', [], $authConfig, $postBytes);
    
        return $resp->data == 'Affected';
    }
}

<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

/**
 * Class Model (Policy Model).
 *
 * @author ab1652759879@gmail.com
 */
class Model
{
    public $owner;
    public $name;
    public $createdTime;
    public $updatedTime;
    public $displayName;
    public $manager;
    public $contactEmail;
    public $type;
    public $parentId;
    public $isTopModel;
    public $users;
    public $title;
    public $key;
    public $children;
    public $modelText;
    public $isEnabled;

    /**
     * @var AuthConfig
     */
    public static $authConfig;

    public static function initConfig(
        string $endpoint,
        string $clientId,
        string $clientSecret,
        string $certificate,
        string $organizationName,
        string $applicationName
    ): void {
        self::$authConfig = new AuthConfig(
            $endpoint,
            $clientId,
            $clientSecret,
            $certificate,
            $organizationName,
            $applicationName
        );
    }

    /**
     * Get all models.
     *
     * @return array
     */
    public static function getModels(): array
    {
        $queryMap = [
            'owner' => self::$authConfig->organizationName,
        ];

        $url = Util::getUrl('get-models', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $models = json_decode($stream->__toString(), true);

        return $models;
    }

    /**
     * Get a specific model.
     *
     * @param string $name
     *
     * @return array|null
     */
    public static function getModel(string $name): ?array
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $name,
        ];

        $url = Util::getUrl('get-model', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $model = json_decode($stream->__toString(), true);

        return $model;
    }

    /**
     * Add a model.
     *
     * @param Model $model
     *
     * @return bool
     */
    public static function addModel(Model $model): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $model->name,
        ];

        $model->owner = self::$authConfig->organizationName;
        $postData = json_encode($model);
        $response = Util::doPost('add-model', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Update a model.
     *
     * @param Model $model
     *
     * @return bool
     */
    public static function updateModel(Model $model): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $model->name,
        ];

        $model->owner = self::$authConfig->organizationName;
        $postData = json_encode($model);
        $response = Util::doPost('update-model', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Delete a model.
     *
     * @param Model $model
     *
     * @return bool
     */
    public static function deleteModel(Model $model): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $model->name,
        ];

        $model->owner = self::$authConfig->organizationName;
        $postData = json_encode($model);
        $response = Util::doPost('delete-model', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 10/8/2016
 * Time: 5:54 PM
 */

namespace Drupal\aluminum_storage\Aluminum\Config;

use Drupal\aluminum\Aluminum\Traits\StaticInvokableTrait;
use Drupal\aluminum_storage\Aluminum\Exception\ConfigException;

class ConfigManager {
  use StaticInvokableTrait;

  protected static $types = [
    'admin',
    'content',
    'appearance',
  ];

  protected static $groupDefaults = [
    'name' => NULL,
    'description' => NULL,
    'weight' => 0,
    'configId' => 'admin'
  ];

  protected static $itemDefaults = [
    'name' => NULL,
    'description' => NULL,
    'defaultValue' => NULL,
    'class' => 'Default',
    'weight' => 0,
    'groupId' => '',
    'fieldArray' => []
  ];

  protected static $config;

  /**
   * Gets an entire config type
   *
   * @param string $configId The machine name of the config type to get
   *
   * Should be one of the types in ConfigManager::$types.
   *
   * @return ConfigInterface The config type specified
   * @throws ConfigException
   */
  static function getConfig($configId) {
    if (!in_array($configId, self::$types)) {
      $err = sprintf("config type (%s) is not valid", $configId);

      throw new ConfigException($err);
    }

    $configGroups = self::loadConfigGroups();
    $groups = [];

    foreach ($configGroups as $groupId => $groupConfig) {
      if ($groupConfig['configId'] == $configId) {

      }
    }

    return new Config($configId, NULL, $groups);
  }

  public static function loadConfigData() {
    if (!isset(self::$config)) {
      $config = \Drupal::configFactory()->getEditable('aluminum_storage.settings');

      self::$config = $config->get('aluminum_storage');
    }

    return self::$config;
  }

  public static function saveConfigData($configData, $configId = NULL) {
    $config = \Drupal::configFactory()->getEditable('aluminum_storage.settings');

    if (!is_null($configId)) {
      $newConfigData = $configData;
      $configData = self::loadConfigData();
      $configData[$configId] = $newConfigData;
    }

    $config->set('aluminum_storage', $configData);
    $config->save();
  }

  public static function loadConfigGroups($configId = NULL) {
    $groups = self::invokeHook('aluminum_storage_groups', self::$groupDefaults);

    if (is_null($configId)) {
      return $groups;
    }

    return array_filter($groups, function ($key) use ($configId) {
      $parts = explode('.', $key);

      return $parts[0] == $configId;
    }, ARRAY_FILTER_USE_KEY);
  }

  public static function loadConfigItems($groupId = NULL) {
    $items = self::invokeHook('aluminum_storage_items', self::$itemDefaults);

    if (is_null($groupId)) {
      return $items;
    }

    return array_filter($items, function ($item) use ($groupId) {
      return (isset($item['groupId']) && $item['groupId'] == $groupId);
    });
  }
}

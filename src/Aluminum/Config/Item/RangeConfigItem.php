<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 10/9/2016
 * Time: 5:09 PM
 */

namespace Drupal\aluminum_storage\Aluminum\Config\Item;


class RangeConfigItem extends DefaultConfigItem {
  protected $fieldArray = [
    '#type' => 'range',
  ];
}
<?php

namespace Void;

abstract class VoidBase {
  static $config;
  public static function setConfig($config) {
    static::$config = $config;
  }
}

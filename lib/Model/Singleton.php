<?php

namespace Void;

class Singleton {
  protected static $instance = null;

  private function __construct();

  public static function getInstance() {
    self::$instance === null && $classname = get_called_class() && self::$instance = new $classname();
    return self::$instance;
  }

  private function __clone() {}
}
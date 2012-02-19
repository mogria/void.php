<?php

namespace Void;

class Table extends Singleton {

  protected static $tables = Array();

  public function __construct($name) {
    $this->scan();
  }

  public static function getInstance($name) {
    !isset(self::$tables[$name]) && self::$tables[$name] = new Table($name);
    return self::$tables[$name];
  }

  public function scan() {

  }
}
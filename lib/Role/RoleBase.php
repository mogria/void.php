<?php

namespace Void;

abstract class RoleBase {
  protected static $id;

  public static $registred = false;

  public function __construct() {
    static::register($this);
  }

  public static function register(RoleBase $role) {
    static $registred = false;
    if(!$registred) {
      $registred = true;
      RoleManager::register(static::$id, $role);
    }
  }

  public function allowedTo($right_name) {
    return isset(static::${$right_name}) ? static::${$right_name} : false;
  }
}

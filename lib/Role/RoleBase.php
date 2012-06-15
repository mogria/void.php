<?php

namespace Void;

abstract class RoleBase {
  protected static $id;

  public function __construct() {
    RoleManager::register(static::$id, $this);
  }

  public function allowedTo($right_name) {
    if(isset(static::$right_name)) {
      static::$right_name === null && static::$right_name = true;
      return (bool)static::$right_name;
    }
    return false;
  }
}

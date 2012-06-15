<?php

namespace Void;

class RoleManager {
  private static $roles = Array(); 

  protected static function validateId($id) {
    if(!is_int($id)) {
      throw new VoidException("Invalid role id format: has to be an integer");
    }

    if(isset(self::$roles[$id])) {
      throw new VoidException("role id($id) is already in use");
    }

  }
  public static function register($id, RoleBase $role) {
    self::validateId($id);
    $roles[$id] = $role;
  }

  public static function get($id) {
    return self::$roles[$id];
  }
}

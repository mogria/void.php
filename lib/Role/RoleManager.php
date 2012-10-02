<?php

namespace Void;

/**
 * gives every role an id
 * you can use the get()-Method to get an role by id
 */
class RoleManager {
  /**
   * an array of all roles who have been registered yet
   * @static
   * @var $roles
   */
  private static $roles = Array(); 

  /**
   * checks if $id is an valid id
   * if not an VoidException is thrown
   *
   * @param $id
   */
  protected static function validate($id, $role) {
    if(!is_int($id)) {
      throw new VoidException("Invalid role id format: has to be an integer");
    }

    if(isset(self::$roles[$id])) {
      $class = new \ReflectionClass($role);
      $class2 = new \ReflectionClass(self::get($id));
      
      if($class->getName() !== $class2->getName()) {
        throw new VoidException("role id($id) is already in use");
      }
    }

  }

  /**
   * registers a new role class
   *
   * @param $id an unique id
   * @param $role an instance of the new role class
   */
  public static function register($id, RoleBase $role) {
    self::validate($id, $role);
    self::$roles[$id] = $role;
  }

  /**
   * returns the role with the given $id
   * if none exists null
   *
   * @param $id
   * @return RoleBase
   */
  public static function get($id) {
    return isset(self::$roles[$id]) ? self::$roles[$id] : null;
  }
}

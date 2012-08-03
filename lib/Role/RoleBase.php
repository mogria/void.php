<?php

namespace Void;

/**
 * abstract base class for all the user roles
 * 
 * if you want to create your own role, create a new class in the roles/ folder
 * and extend this class. You can also base a new role on one of your Role classes.
 *
 * inside the class you can set static properties to true. every static property set to true
 * stands for somewhing the user can do if he has the role. you can call the 
 * allowedTo method to get this
 *
 * you can also disallow the user something if you set a static property to false
 *
 * a little example
 *
 * <code>
 * <?php
 *
 * class MyUserRole extends RoleBase {
 *   // an unique id is needed for the RoleManager
 *   static $id = 3;
 *   static $comment = true;
 *   static $vote = true;
 * }
 *
 * class MyAdminRole extends MyUserRole {
 *   static $id = 1;
 *   static $administrate = true;
 *   static $vote = false;
 * }
 *
 * $user = new MyUserRole();
 * $admin = new MyAdminRole();
 *
 * $user->allowedTo("comment"); // true
 * $user->allowedTo("vote"); // true
 * $user->allowedTo("administrate"); // false, because not set
 * $user->allowedTo("destroy_database"); // false, because not set
 *
 *
 * $admin->allowedTo("comment"); // true, because inheritance
 * $admin->allowedTo("vote"); // false, because set to false
 * $admin->allowedTo("administrate"); // true
 * $admin->allowedTo("destroy_database"); // false, because not set
 * </code>
 */
abstract class RoleBase {
  /**
   * must be set to an unique id for every subclass
   * @var int $id
   */
  protected static $id;

  /**
   * Constructor
   * registers itself to the RoleManager (if not already done)
   *
   * if you want to create a role class, that doesn't register to the RoleManager,
   * simply overwrite this Method without calling parent::__construct()
   */
  public function __construct() {
    static::register($this);
  }

  /**
   * registers this class to the RoleManager (only one time)
   * @param RoleBase $this - an instance of this object
   */
  protected static function register(RoleBase $role) {
    static $registred = false;
    if(!$registred) {
      $registred = true;
      RoleManager::register(static::$id, $role);
    }
  }

  /**
   * returns (true/false) wheter a user of the given
   * role is allowed to do something ($right_name)
   *
   * @param the name of the thing the role is (not) allowed to do
   * @return bool
   */
  public function allowedTo($right_name) {
    return isset(static::${$right_name}) ? static::${$right_name} : false;
  }

  /**
   * easier access to allowedTo()
   *
   * @param the name of the thing the role is (not) allowed to do
   * @return bool
   * @see allowedTo
   */
  public function __get($right_name) {
    return $this->allowedTo($right_name);
  }

  /**
   * returns the id of the role
   *
   * @static
   * @return int $id
   */
  public static function getId() {
    return static::$id;
  }
}

<?php

namespace Void;

require_once 'config/consts.php';
require_once 'autoload.php';

class UserRole extends RoleBase {
  protected static $id = 15;

  static $view = true;
  static $edit = true;
  static $vote = true;
  static $something = false;
}

class AdminRole extends UserRole {
  protected static $id = 27;

  static $vote = false;
  static $admin = true;
}

class ReservedIdRole extends RoleBase {
  protected static $id = 15; // this id is already used by UserRole
}

class InvalidIdFormatRole extends RoleBase {
  protected static $id = "not an integer";
}

class RoleBaseTest extends \PHPUnit_Framework_TestCase {
  
  protected $user;
  protected $admin;

  public function setUp() {
    $this->user = new UserRole();
    $this->admin = new AdminRole();
 }

  public function testRoleManagerGet() {
    $this->assertEquals($this->user, RoleManager::get(15));
    $this->assertEquals($this->admin, RoleManager::get(27));
  }

  public function testMultipleInstances() {
    new UserRole();
    new UserRole();
    new AdminRole();
    new AdminRole();
  }

  public function testAllowedToUser() {
    $this->assertFalse($this->user->allowedTo("something"));
    $this->assertTrue($this->user->allowedTo("vote"));
    $this->assertTrue($this->user->allowedTo("edit"));
    $this->assertTrue($this->user->allowedTo("view"));
    $this->assertFalse($this->user->allowedTo("admin"));
    $this->assertFalse($this->user->allowedTo("some_random_shit"));
  }

  public function testAllowedToAdmin() {
    $this->assertFalse($this->admin->allowedTo("something"));
    $this->assertFalse($this->admin->allowedTo("vote"));
    $this->assertTrue($this->admin->allowedTo("edit"));
    $this->assertTrue($this->admin->allowedTo("view"));
    $this->assertTrue($this->admin->allowedTo("admin"));
    $this->assertFalse($this->admin->allowedTo("some_random_shit"));
  }

  /**
   * @expectedException \Void\VoidException
   */
  public function testInvalidIdFormat() {
    new InvalidIdFormatRole();
  }

  /**
   * @expectedException \Void\VoidException
   */
  public function testReservedId() {
    new ReservedIdRole();
  }
}

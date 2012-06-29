<?php

namespace Void;

require_once 'config/consts.php';
require_once 'autoload.php';

require_once __DIR__  . DS . 'roles/UserRole.php';
require_once __DIR__  . DS . 'roles/AdminRole.php';
require_once __DIR__  . DS . 'roles/ReservedIdRole.php';
require_once __DIR__  . DS . 'roles/InvalidIdFormatRole.php';

class RoleManagerTest extends \PHPUnit_Framework_TestCase {

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
    new AdminRole();
    new UserRole();
    new AdminRole();
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
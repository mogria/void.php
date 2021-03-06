<?php

namespace Void;

require __DIR__ . DIRECTORY_SEPARATOR . 'test_bootstrap.php';

require_once __DIR__  . DS . 'roles/UnknownRole.php';
require_once __DIR__  . DS . 'roles/RegistredRole.php';
require_once __DIR__  . DS . 'roles/AdminRole.php';
require_once __DIR__  . DS . 'invalid_roles/ReservedIdRole.php';
require_once __DIR__  . DS . 'invalid_roles/InvalidIdFormatRole.php';

class RoleManagerTest extends \PHPUnit_Framework_TestCase {

  public function setUp() {
    $this->user = new RegistredRole();
    $this->admin = new AdminRole();
  }

  public function testRoleManagerGet() {
    $this->assertEquals($this->user, RoleManager::get(15));
    $this->assertEquals($this->admin, RoleManager::get(27));
  }

  public function testMultipleInstances() {
    new RegistredRole();
    new AdminRole();
    new RegistredRole();
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

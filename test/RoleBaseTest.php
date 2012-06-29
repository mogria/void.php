<?php

namespace Void;

require_once 'config/consts.php';
require_once 'autoload.php';


require_once __DIR__  . DS . 'roles/UserRole.php';
require_once __DIR__  . DS . 'roles/AdminRole.php';
require_once __DIR__  . DS . 'roles/ReservedIdRole.php';
require_once __DIR__  . DS . 'roles/InvalidIdFormatRole.php';

class RoleBaseTest extends \PHPUnit_Framework_TestCase {
  
  protected $user;
  protected $admin;

  public function setUp() {
    $this->user = new UserRole();
    $this->admin = new AdminRole();
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
}
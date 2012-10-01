<?php

namespace Void;

require __DIR__ . DIRECTORY_SEPARATOR . 'test_bootstrap.php';

require_once 'test/roles/UnknownRole.php';
require_once 'test/roles/RegistredRole.php';

class AuthentificationTest extends \PHPUnit_Framework_TestCase {
  
  
  protected $user;
  protected static $user_id = null;

  public function setUp() {
    $_SESSION = Array();
    if(self::$user_id === null) {
      $this->user = User::create(Array('name' => 'testuser', 'text_password' => 'secretpasswd15', 'role' => 15));
      self::$user_id = $this->user->id;
      if(!$this->user->save()) {
        throw new \Exception("couldn't save user to db");
      }
    } else {
      $this->user = User::find(self::$user_id);
    }
    User::auth_init(true);
  }

  public function testAuthInit() {
    $this->assertEquals(null, Session::user()->id);
    $this->assertEquals("Anonymous", Session::user()->name);
  }

  public function testLogin() {
    $this->user->text_password = "kack";
    $this->assertFalse($this->user->login());
    $this->user->text_password = "secretpasswd15";
    $this->assertTrue($this->user->login());
    $this->assertEquals(null, Session::user(null));
  }

  public function testLogout() {
    $this->user->text_password = "secretpasswd15";
    $this->user->login();
    $this->assertTrue(is_int(Session::user()->id) && null !== Session::user()->id);
    $this->user->logout();
    $this->assertEquals(null, Session::user()->id);
  }

  public function testGetRole() {
    $this->assertTrue($this->user->get_role() instanceof UnknownRole);
    $this->user->text_password = "secretpasswd15";
    $this->assertTrue($this->user->login());
    $this->assertTrue($this->user->get_role() instanceof RegistredRole);
    $this->assertTrue(Session::user()->get_role() instanceof RegistredRole);
    $this->user->logout();
    $this->assertTrue($this->user->get_role() instanceof UnknownRole);
  }
}

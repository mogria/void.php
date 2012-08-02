<?php

namespace Void;

require_once 'config/consts.php';
require_once 'autoload.php';
require_once 'test/Models/User.php';


class AuthentificationTest extends \PHPUnit_Framework_TestCase {
  
  
  protected $user;
  protected static $user_id = null;
  public function setUp() {
    // load the config (for the Hash class)
    $overwrite_environment = TEST;
    require 'config/environments.php';
    Booter::boot(true);

    $_SESSION = Array();
    if(self::$user_id === null) {
      $this->user = User::create(Array('name' => 'testuser', 'text_password' => 'secretpasswd15', 'role' => 15));
      //var_dump($this->user);
      self::$user_id = $this->user->id;
      if(!$this->user->save()) {
        throw new \Exception("couldn't save user to db");
      }
    } else {
      $this->user = User::find(self::$user_id);
    }
    Session::user(null);
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
    $this->assertFalse(Session::user() === null);
    $this->user->logout();
    $this->assertEquals(null, Session::user());
  }

  public function testGetRole() {
    $this->assertTrue($this->user->get_role() instanceof UnknownRole);
    $this->user->text_password = "secretpasswd15";
    $this->user->login();
    $this->assertTrue($this->user->get_role() instanceof RegistredRole);
    $this->assertTrue(Session::user()->get_role() instanceof RegistredRole);
    $this->user->logout();
    $this->assertTrue($this->user->get_role() instanceof UnknownRole);
  }
}

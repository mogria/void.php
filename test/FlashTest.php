<?php

namespace Void;


require_once 'config/consts.php';
require_once 'autoload.php';
require_once 'config/environments.php';

class FlashTest extends \PHPUnit_Framework_TestCase {
  public function setUp() {
    $_SESSION = Array();
  }

  public function testMessage() {
    Flash::message("error", "Error!");
    $this->assertEquals("error", $_SESSION[Flash::SESSION_VARIABLE][0]->getType());
    $this->assertEquals("Error!", $_SESSION[Flash::SESSION_VARIABLE][0]->getMessage());
    $this->assertEquals(1, count($_SESSION[Flash::SESSION_VARIABLE]));
    Flash::message("success", "Success!");
    $this->assertEquals("success", $_SESSION[Flash::SESSION_VARIABLE][1]->getType());
    $this->assertEquals("Success!", $_SESSION[Flash::SESSION_VARIABLE][1]->getMessage());
    $this->assertEquals(2, count($_SESSION[Flash::SESSION_VARIABLE]));
    Flash::message("info", "Info!");
    $this->assertEquals("info", $_SESSION[Flash::SESSION_VARIABLE][2]->getType());
    $this->assertEquals("Info!", $_SESSION[Flash::SESSION_VARIABLE][2]->getMessage());
    $this->assertEquals(3, count($_SESSION[Flash::SESSION_VARIABLE]));
    Flash::message("warning", "Warning!");
    $this->assertEquals("warning", $_SESSION[Flash::SESSION_VARIABLE][3]->getType());
    $this->assertEquals("Warning!", $_SESSION[Flash::SESSION_VARIABLE][3]->getMessage());
    $this->assertEquals(4, count($_SESSION[Flash::SESSION_VARIABLE]));
  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testMessageInvalidArgumentException() {
    Flash::message("void", "test");
  }

  public function testMessageDouble() {
    Flash::message("warning", "Warning!");
    Flash::message("warning", "Warning!");
    $this->assertEquals(1, count($_SESSION[Flash::SESSION_VARIABLE]));
  }

  public function test__callStatic() {
    Flash::error("Error!");
    Flash::warning("Warning!");
    Flash::info("Info!");
    Flash::success("Success!");
    Flash::success("yeah!!", "Yippe!");
    $this->assertEquals(6, count($_SESSION[Flash::SESSION_VARIABLE]));
  }


  /**
   * @expectedException InvalidArgumentException
   */
  public function test__callStaticInvalidArgumentException() {
    Flash::dafuq("test");
  }

  public function testToArray() {
    $this->assertTrue(is_array(Flash::toArray()));
    Flash::warning("Warning!");
    $this->assertEquals(1, count(Flash::toArray()));
  }

  public function testClear() {
    Flash::warning("Warning1!");
    Flash::warning("Warning2!");
    Flash::warning("Warning3!");
    $this->assertEquals(3, count($_SESSION[Flash::SESSION_VARIABLE]));
    Flash::clear();
    $this->assertEquals(0, count($_SESSION[Flash::SESSION_VARIABLE]));
  }

  public function testRemove() {
    Flash::warning("Warning3!");
    $this->assertEquals(1, count($_SESSION[Flash::SESSION_VARIABLE]));
    Flash::remove(new FlashMessage("warning", "Warning3!"));
    $this->assertEquals(0, count($_SESSION[Flash::SESSION_VARIABLE]));
  }
}

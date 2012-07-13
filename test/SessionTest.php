<?php

namespace Void;

require_once 'config/consts.php';
require_once 'autoload.php';

class SessionTest extends \PHPUnit_Framework_TestCase {
  
  public function setUp() {
    $_SESSION = Array();
  }

  public function tearDown() {
  }
  
  public function testClassExists() {
    $this->assertTrue(class_exists('Void\\Session'));
  }

  public function testCallStatic() {
    $this->assertTrue(method_exists('Void\\Session', '__callStatic'));
  }

  public function testCallStaticSet() {
    Session::test('void');
    $this->assertEquals("void", $_SESSION['test']);
  }

  public function testCallStaticGet() {
    Session::test('void');
    $this->assertEquals("void", Session::test());
    $this->assertEquals(null, Session::undefined());
  }

  public function testCallStaticDelete() {
    //$this->assertFalse(Session::testDelete());
    Session::test('void');
    $this->assertTrue(Session::test_delete());
  }

  public function testCallStaticExists() {
    //$this->assertFalse(Session::test_exists());
    Session::test('void');
    $this->assertTrue(Session::testExIsts());
  }

  public function testSet() {
    $ret = Session::set('void', 15);
    $this->assertEquals(15, $ret);
    $this->assertEquals(15, $_SESSION['void']);
  }

  public function testGet() {
    Session::set('void', 15);
    Session::set('void2', "wheeee");
    $this->assertEquals(15, Session::get('void'));
    $this->assertEquals("wheeee", Session::get('void2'));
  }

  public function testGetUndefined() {
    $this->assertEquals(null, Session::get('undefined'));
  }

  public function testExists() {
    Session::set('void', 15);
    $this->assertEquals(false, Session::exists('undefined'));
    $this->assertEquals(true, Session::exists('void'));
  }

  public function testDelete() {
    $this->assertFalse(Session::exists('void'));
    Session::set('void', 15);
    $this->assertTrue(Session::exists('void'));
    Session::delete('void');
    $this->assertFalse(Session::exists('void'));
  }

  public function testDeleteUndefined() {
    Session::set('void', 15);
    $this->assertTrue(Session::delete('void'));
    $this->assertFalse(Session::delete('undefined'));
  }

  public function testEndsWith() {
    $str = "voidexception";
    $ending = "exception";
    $this->assertTrue(Session::ends_with($str, $ending));
    $this->assertEquals("void", $str);
    $this->assertEquals("exception", $ending);
  }

  public function testEndsWithUnderline() {
    $str = "void_exception";
    $ending = "exception";
    $this->assertTrue(Session::ends_with($str, $ending));
    $this->assertEquals("void", $str);
    $this->assertEquals("exception", $ending);
  }

  public function testEndsWithDifferentCase() {
    $str = "voidExcEpTiON";
    $ending = "excePTion";
    $this->assertTrue(Session::ends_with($str, $ending));
    $this->assertEquals("void", $str);
    $this->assertEquals("ExcEpTiON", $ending);
  }

  public function testEndsNotWith() {
    $str = "void";
    $ending = "exception";
    $this->assertFalse(Session::ends_with($str, $ending));
    $this->assertEquals("void", $str);
    $this->assertEquals("exception", $ending);
  }
}

<?php

namespace Void;

require_once 'config/consts.php';
require_once 'autoload.php';

class StringTest extends \PHPUnit_Framework_TestCase {
  
  public function setUp() {
    $this->string = new String("mega:awesome:string");
    $this->string2 = s("str");
  }

  public function test__toString() {
    $this->assertEquals("test string", (string)s("test string"));
    $this->assertEquals("test string", s("test string")->__toString());
    $this->assertEquals("", (string)s(false));
    $this->assertEquals("", (string)s(NULL));
    $this->assertEquals("1", (string)s(true));
  }

  public function testCamelize() {
    $this->assertEquals("WhatsUp", (string)s("whats_up")->camelize());
    $this->assertEquals("ThisIsAVeryLongUncamelizedString", (string)s("this_is_a_very_long_uncamelized_string")->camelize());
    $this->assertEquals("Ab", (string)s("ab")->camelize());
    $this->assertEquals("A", (string)s("a")->camelize());
    $this->assertEquals("", (string)s("")->camelize());
  }

  public function testUncamelize() {
    $this->assertEquals("whats_up", (string)s("WhatsUp")->uncamelize());
    $this->assertEquals("this_is_a_very_long_uncamelized_string", (string)s("ThisIsAVeryLongUncamelizedString")->uncamelize());
    $this->assertEquals("ab", (string)s("Ab")->uncamelize());
    $this->assertEquals("a", (string)s("A")->uncamelize());
    $this->assertEquals("", (string)s("")->uncamelize());

  }

  public function tearDown() {
  }
}

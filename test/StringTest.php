<?php

namespace Void;

require __DIR__ . DIRECTORY_SEPARATOR . 'test_bootstrap.php';

class dynamic_call_test {
  public function call(&$arg1) {
    $args = Array(&$arg1);
    $this->__call('call', $args);
  }
  public function __call($method, $args) {
    $args[0] = 15;
  }
}

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

  /**
   * @expectedException \BadMethodCallException
   */
  public function testCall() {
    $this->string->a_random_method_which_doesnt_exist();
  }

  public function testCallReplace() {
    $str_before = (string)$this->string;
    $back = $this->string->replace(":", "|");
    $this->assertTrue($back instanceof String);
    $this->assertEquals(str_replace(":", "|", $str_before), (string)$back);
  }

  public function testCallIReplace() {
    $str_before = (string)$this->string;
    $back = $this->string->ireplace("A", "V");
    $this->assertTrue($back instanceof String);
    $this->assertEquals(str_ireplace("A", "V", $str_before), (string)$back);
  }

  public function testCallPregReplace() {
    $str_before = (string)$this->string;
    $back = $this->string->preg_replace("/(.):(.)/", "\\2:\\1");
    $this->assertTrue($back instanceof String);
    $this->assertEquals(preg_replace("/(.):(.)/", "\\2:\\1", $str_before), (string)$back);
  }

  public function testCallPregReplaceCallback() {
    $callback = function($value) {
      return strtoupper($value[2] . "|" . $value[1]);
    };
    $str_before = (string)$this->string;
    $back = $this->string->preg_replace_callback("/(.):(.)/", $callback);
    $this->assertTrue($back instanceof String);
    $this->assertEquals(preg_replace_callback("/(.):(.)/", $callback, $str_before), (string)$back);
  }

  public function testCallPos() {
    $str_before = (string)$this->string;
    $back = $this->string->pos(":");
    $this->assertFalse($back instanceof String);
    $this->assertEquals(strpos($str_before, ":"), $back);
  }

  public function testCallIPos() {
    $str_before = (string)$this->string;
    $back = $this->string->ipos("S");
    $this->assertFalse($back instanceof String);
    $this->assertEquals(stripos($str_before, "S"), $back);
  }

  public function testCallRPos() {
    $str_before = (string)$this->string;
    $back = $this->string->rpos(":");
    $this->assertFalse($back instanceof String);
    $this->assertEquals(strrpos($str_before, ":"), $back);
  }

  public function testCallIRPos() {
    $str_before = (string)$this->string;
    $back = $this->string->ripos("S");
    $this->assertFalse($back instanceof String);
    $this->assertEquals(strripos($str_before, "S"), $back);
  }

  public function testCallSubstr() {
    $str_before = (string)$this->string;
    $back = $this->string->substr(1, -3);
    $this->assertTrue($back instanceof String);
    $this->assertEquals(substr($str_before, 1, -3), (string)$back);
  }

  public function testCallExplode() {
    $str_before = (string)$this->string;
    $back = $this->string->explode(":");
    $this->assertFalse($back instanceof String);
    $this->assertEquals(explode(":", $str_before), $back);
  }

  public function testCallSplit() {
    $str_before = (string)$this->string;
    $back = $this->string->split();
    $this->assertFalse($back instanceof String);
    $this->assertEquals(str_split($str_before), $back);
  }

  public function testCallSplitWithOptionalParameter() {
    $str_before = (string)$this->string;
    $back = $this->string->split(3);
    $this->assertFalse($back instanceof String);
    $this->assertEquals(str_split($str_before, 3), $back);
  }

  public function testCallRepeat() {
    $str_before = (string)$this->string;
    $back = $this->string->repeat(5);
    $this->assertTrue($back instanceof String);
    $this->assertEquals(str_repeat($str_before, 5), (string)$back);
  }

  public function testCallMatch() {
    $str_before = (string)$this->string;
    $back = $this->string->match("/:\w+:/");
    $this->assertFalse($back instanceof String);
    $this->assertEquals(preg_match("/:\w+:/", $str_before), $back);
  }

  public function test_dyamic_call() {
    $test = new dynamic_call_test();
    $value = 14;
    $test->call($value);
    $this->assertEquals(15, $value);
  }

  public function testCallMatchAll() {
    $matches = Array();
    $matches2 = Array();
    $str_before = (string)$this->string;
    $back = $this->string->match_all("/\w+:/", $matches);
    $this->assertFalse($back instanceof String);
    $this->assertEquals(preg_match_all("/\w+:/", $str_before, $matches2), $back);
    $this->assertEquals($matches, $matches2);
  }
  
  public function testCallLength() {
    $str_before = (string)$this->string;
    $back = $this->string->length();
    $this->assertFalse($back instanceof String);
    $this->assertEquals(strlen($str_before), $back);
  }

  public function testCallAliases() {
    $this->string->len();
    $this->string->substring(0);
  }

  /**
   *
   * @expectedException \InvalidArgumentException
   */
  public function testOffsetGetEmpty() {
    $this->string[""];
  }


  /**
   * 
   * @expectedException \InvalidArgumentException
   */
  public function testOffsetGetRandom() {
    $this->string["asdfadfasdfadsfafasdfasdfasdferasdfvqwf"];
  }

  public function testOffsetGetWithNumber() {
    $len = strlen($this->string) + 1;
    $string = (string)$this->string;
    $this->assertEquals(substr($string, 4, 1), (string)$this->string[4]);
    $this->assertEquals(substr($string, $len, 1), (string)$this->string[$len]);
    $this->assertEquals(substr($string, -4, 1), (string)$this->string[-4]);
    $this->assertEquals(substr($string, -$len, 1), (string)$this->string[-$len]);
  }

  public function testOffsetGetWithNumber1AndColon() {
    $len = strlen($this->string) + 1;
    $string = (string)$this->string;
    $this->assertEquals(substr($string, 4), (string)$this->string["4:"]);
    $this->assertEquals(substr($string, $len), (string)$this->string[$len . ":"]);
    $this->assertEquals(substr($string, -4), (string)$this->string["-4:"]);
    $this->assertEquals(substr($string, -$len), (string)$this->string["-" . $len . ":"]);
  }

  public function testOffsetGetWithNumer2AndColon() {
  }

  public function testOffsetGetWithColon() {
    $string = $this->string[":"];
    $this->assertTrue($string instanceof String);
    $this->assertTrue($this->string instanceof String);
    $this->assertTrue($this->string == $string);
    $this->assertTrue($this->string !== $string);
  }

  public function testOffsetGetWithTwoNumbers() {
    $string = (string)$this->string;
    $this->assertEquals(substr($string, 2, 2), (string)$this->string["2:4"]);
    $this->assertEquals(substr($string, 2, 2), (string)$this->string["4:2"]);
    $this->assertEquals(substr($string, -12, 3), (string)$this->string["-12:10"]);
    $this->assertEquals(substr($string, 2, -4), (string)$this->string["2:-4"]);
    $this->assertEquals(substr($string, -5, -1), (string)$this->string["-5:-1"]);
    $this->assertEquals(substr($string, -5, -1), (string)$this->string["-1:-5"]);
  }

  public function testOffsetSet() {
    $this->string[":4"] = "trol";
    $this->assertEquals("trol:awesome:string", (string)$this->string);
    $this->string[-4] = "rrrrrrrr";
    $this->assertEquals("trol:awesome:strrrrrrrring", (string)$this->string);
    $this->string["-2:"] = "nnnnnnnnng";
    $this->assertEquals("trol:awesome:strrrrrrrrinnnnnnnnng", (string)$this->string);
    $this->string["-1:-3"] = "";
    $this->assertEquals("trol:awesome:strrrrrrrrinnnnnnng", (string)$this->string);
    $this->string["5:12"] = "fantastic";
    $this->assertEquals("trol:fantastic:strrrrrrrrinnnnnnng", (string)$this->string);
    $this->string["-1:"] = "ggg";
    $this->assertEquals("trol:fantastic:strrrrrrrrinnnnnnnggg", (string)$this->string);
  }

  public function testOffsetUnset() {
    unset($this->string[4]);
    $this->assertEquals("megaawesome:string", (string)$this->string);
    unset($this->string[-7]);
    $this->assertEquals("megaawesomestring", (string)$this->string);
    unset($this->string["-6:"]);
    $this->assertEquals("megaawesome", (string)$this->string);
    unset($this->string["-4:-7"]);
    $this->assertEquals("megasome", (string)$this->string);
    unset($this->string["4:8"]);
    $this->assertEquals("mega", (string)$this->string);
  }

}

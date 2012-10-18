<?php

namespace Void;

require __DIR__ . DIRECTORY_SEPARATOR . 'test_bootstrap.php';

class StringExpressionPlaceholderTest extends \PHPUnit_Framework_TestCase {
  
  protected static $data = Array(
    //   0   1       2            3           4            5
    //   id  offset  name         char_range  numerically  delimiter
    Array(1, 0,      "name",      "a-z",      "{1}",       "/"),
    Array(2, 14,     "othername", "^\\-",     "*",         "-"),
    Array(3, 16,     "newname",   "0-9",      "+",         "/"),
    Array(4, 3,      "dafuq",     "^\\+",     "{0,}",      "+"),
    Array(5, 5,      "nameless",  "^\\s",     "{2,3}",     " "),
    Array(6, 7,      "asd",       "a-zA-Z",   "?",         "/")
  );

  protected static $gen_data = Array(
    //    0         1                2
    //    optional  multiple_blocks  expression
    Array(false,    false,           "(\\/?(?:[a-z]+\\/?){1})"),
    Array(true,     true,            "(\\-?(?:[^\\-]+\\-?)*)"),
    Array(false,    true,            "(\\/?(?:[0-9]+\\/?)+)"),
    Array(true,     true,            "(\\+?(?:[^\\+]+\\+?){0,})"),
    Array(false,    true,            "( ?(?:[^\\s]+ ?){2,3})"),
    Array(true,     false,           "(\\/?(?:[a-zA-Z]+\\/?)?)")
  );

  protected static $placeholders;

  public static function setUpBeforeClass() {
    foreach(self::$data as $record) {
      self::$placeholders[] = new SimpleExpressionPlaceholder($record[0], $record[1], $record[2], $record[3], $record[4], $record[5]);
    }
  }

  public function setUp() {
  }

  public function testConstruct() {
    foreach(self::$placeholders as $key => $placeholder) {
      $this->assertEquals($placeholder->getId(), self::$data[$key][0]);
      $this->assertEquals($placeholder->getOffset(), self::$data[$key][1]);
      $this->assertEquals($placeholder->getName(), self::$data[$key][2]);
      $this->assertEquals($placeholder->getCharRange(), self::$data[$key][3]);
      $this->assertEquals($placeholder->getNumerically(), self::$data[$key][4]);
      $this->assertEquals($placeholder->getDelimiter(), self::$data[$key][5]);
    }
  }

  public function testIsOptional() {
    foreach(self::$placeholders as $key => $placeholder) {
      $this->assertEquals($placeholder->isOptional(), self::$gen_data[$key][0]);
    }
  }

  public function testHasMultipleBlocks() {
    foreach(self::$placeholders as $key => $placeholder) {
      $this->assertEquals($placeholder->hasMultipleBlocks(), self::$gen_data[$key][1]);
    }
  }

  public function testGetExpression() {
    foreach(self::$placeholders as $key => $placeholder) {
      $this->assertEquals($placeholder->getExpression(), self::$gen_data[$key][2]);
    }
  }
}

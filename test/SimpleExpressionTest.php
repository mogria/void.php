<?php

namespace Void;

require __DIR__ . DIRECTORY_SEPARATOR . 'test_bootstrap.php';

class SimpleExpressionTest extends \PHPUnit_Framework_TestCase {
  
  protected $simple_expression;

  public function setUp() {
    $this->simple_expression = new SimpleExpression('lets extend :sample fucking pattern with a :[0-9]number', '/');
  }

  public function tearDown() {

  }

  public function test_Construct() {
  }

  public function testCompile() {
  }

  public function testCompilePlaceholder() {
  }

  public function testMatch() {
    $this->simple_expression->match('lets extend this fucking pattern with a 23');
  }

  public function testReplace() {
  }

  public function testReplacement() {
  }
}

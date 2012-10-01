<?php

namespace Void;

require __DIR__ . DIRECTORY_SEPARATOR . 'test_bootstrap.php';

class RequestTest extends \PHPUnit_Framework_TestCase {
  protected $request;

  public function setUp($script_name = "/void.php/index.php", $php_self = "/void.php/index.php/test/method/param1/param2") {
    $_SERVER['SCRIPT_NAME'] = $script_name;
    $_SERVER['PHP_SELF']    = $php_self;
    $this->request = new Request();
  }

  public function testGetArray() {
    $this->assertEquals(Array(
      'test',
      'method',
      'param1',
      'param2'
    ), Request::getArray());

    $this->setUp('/index.php', '/index.php');

    $this->assertEquals(Array(
    ), Request::getArray());
  }

  public function test__get() {
    $this->assertEquals('test', $this->request->controller);
    $this->assertEquals('method', $this->request->method);
    $this->assertEquals(Array('param1', 'param2'), $this->request->params);

    $this->setUp('/index.php', '/index.php');

    $this->assertEquals(null, $this->request->controller);
    $this->assertEquals(null, $this->request->method);
    $this->assertEquals(Array(), $this->request->params);
  }
}

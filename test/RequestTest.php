<?php

namespace Void;

require __DIR__ . DIRECTORY_SEPARATOR . 'test_bootstrap.php';

class RequestTest extends \PHPUnit_Framework_TestCase {
  protected $request;

  public function setUp() {
    Router::configure(function($route) {
      $route->match('/:+g', '/:g');
    });
    $this->request = new Request('/test/method/param1/param2');
  }

  public function testGetArray() {
    $this->assertEquals(Array(
      'test',
      'method',
      'param1',
      'param2'
    ), $this->request->toArray());

    $empty = new Request('/');

    $this->assertEquals(Array(
    ), $empty->toArray());
  }

  public function test__get() {
    $this->assertEquals('test', $this->request->controller);
    $this->assertEquals('method', $this->request->method);
    $this->assertEquals(Array('param1', 'param2'), $this->request->params);

    $empty = new Request('/');
    $this->assertEquals(null, $empty->controller);
    $this->assertEquals(null, $empty->method);
    $this->assertEquals(Array(), $empty->params);
  }
}

<?php

namespace Void;

require_once 'config/consts.php';
require_once 'autoload.php';
require_once 'test/config/routes.php';

class RouteTest extends \PHPUnit_Framework_TestCase {
  
  protected $route;
  
  protected $routes = Array(
    '/' => Array('/', '/pages/home', "/^\\/$/D", Array()),
    '/about' => Array('/about', '/pages/about', "/^\\/about$/D", Array()),
    '/_:action' =>  Array('/_:action', '/admin/:action', "/^\\/_([^\\/]+)$/D", Array(':action')),
    '/add/:number1+:number2' => Array('/add/:number1+:number2', '/calculator/add/:number1/:number2', "/^\\/add\\/([^\\/]+)\\+([^\\/]+)$/D", Array(':number1', ':number2'))
  );
  
  protected $results = Array(
    '/' => Array(
      Array('/', '/pages/home'),
      Array('/about', false),
      Array('/random', false)),
    '/about' => Array(
      Array('/about', '/pages/about'),
      Array('/', false),
      Array('about', false),
      Array('/about/14', false),
      Array('/dafuq', false)),
    '/_:action' => Array(
      Array('/_', false),
      Array('/_a', '/admin/a'),
      Array('/_dafuq_random_string15+', '/admin/dafuq_random_string15+'),
      Array('/_0', '/admin/0'),
      Array('/_0/1', false)),
    '/add/:number1+:number2' => Array(
      Array('/', false),
      Array('/about', false),
      Array('/add', false),
      Array('/add/', false),
      Array('/add/+', false),
      Array('/add/1+', false),
      Array('/add/+1', false),
      Array('/add/1+1/', false),
      Array('/add/1+1', '/calculator/add/1/1'))
  );

  public function setUp() {
  }

  /**
   * @dataProvider provideRouteData
   */
  public function testCompile($url, $target, $pattern, $names) {
    $route = new Route($url, $target);
    $this->assertEquals($url, $route->getUrl());
    $this->assertEquals($target, $route->getTarget());
    $this->assertEquals($pattern, $route->getPattern());
    $this->assertEquals($names, $route->getNames());
  }
  
  public function provideRouteData() {
    return $this->routes;
  }

  /**
   * @dataProvider provideRequestData 
   */
  public function testRequest($route, $request, $result) {
    $this->assertEquals($result, $route->request($request));
  }
  
  public function provideRequestData() {
    $data = Array();
    foreach($this->routes as $route) {
      foreach($this->results[$route[0]] as $result) {
        $data[] = Array(new Route($route[0], $route[1]), $result[0], $result[1]);
      }
    }
    return $data;
  }
}

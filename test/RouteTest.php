<?php

namespace Void;

require __DIR__ . DIRECTORY_SEPARATOR . 'test_bootstrap.php';

class RouteTest extends \PHPUnit_Framework_TestCase {
  
  protected $route;
  
  protected $routes = Array(
    '/' => Array('/', '/pages/home', "/^\\/$/D", Array()),
    '/about' => Array('/about', '/pages/about', "/^\\/about$/D", Array()),
    '/_:action' =>  Array('/_:action', '/admin/:action', "/^\\/_((?:[^\\/]+\\/?){1})$/D", Array(':action')),
    '/add/:[0-9]++number' => Array('/add/:[0-9]++number', '/calculator/add/:number', "/^\\/add\\/((?:[0-9]+\\+?)+)$/D", Array(':number'))
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
    '/add/:[0-9]++number' => Array(
      Array('/', false),
      Array('/about', false),
      Array('/add', false),
      Array('/add/', false),
      Array('/add/+', false),
      Array('/add/1+', '/calculator/add/1'),
      Array('/add/+1', false),
      Array('/add/1+1/', false),
      Array('/add/1+1', '/calculator/add/1/1'))
  );

  protected $links = Array(
    '/' => Array(
      Array(Array(), '/')),
    '/about' => Array(
      Array(Array(), '/about')),
    '/_:action' => Array(
      Array(Array('test'), '/_test'),
      Array(Array('some_other_random_string'), '/_some_other_random_string')),
    '/add/:[0-9]++number' => Array(
      Array(Array(Array('1','3', '5', '5')), '/add/1+3+5+5'),
      Array(Array(Array('random_string')), '/add/'))
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

  public function provideLinkData() {
    $data = Array();
    foreach($this->routes as $route) {
      foreach($this->links[$route[0]] as $link) {
        $data[] = Array(new Route($route[0], $route[1]), $link[0], $link[1]);
      }
    }
    return $data;
  }

  /**
   * @dataProvider provideLinkData
   */
  public function testLink($route, $param, $result) {
    //print_r(func_get_args());
    $this->assertEquals($result, call_user_func_array(Array($route, 'link'), $param));
  }
}

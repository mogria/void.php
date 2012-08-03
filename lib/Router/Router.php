<?php

namespace Void;
use \InvalidArgumentException;
use \BadMethodCallException;

/**
 * This class generates links and redirects
 *
 * @author Mogria
 * @package void.php
 */
class Router extends VoidBase {
    
  protected $path_link;

  protected $routes = Array();
  
  protected static $instance;
  
  protected function __construct() {}
  private function __clone() {}
  
  public function match($url, $target, $link_function = null) {
    $link_function = is_string($link_function) ? self::generateLinkFunction($link_function) : self::generateLinkFunction($url);
    if(isset($this->routes[$link_function])) {
      throw new InvalidArgumentException("the link function '$link_function' already exists!");
    }
    $this->routes[$link_function] = new Route($url, $target);
  }
  
  public static function generateLinkFunction($url) {
    return trim(preg_replace('/_+/', '_', preg_replace('/[^a-zA-Z0-9]+/', '_', $url)), "_");
  }
  
  public static function configure($closure) {
    $route = new Router();
    $closure($route);
    self::$instance = $route;
  }
  
  public static function getRoutes() {
      return self::$instance->getRoute();
  }
  
  public function getRoute() {
    return $this->routes;
  }

  public static function request($path_info) {
    $result = "";
    $routes = self::getRoutes();
    foreach($routes as $route) {
      if(($back = $route->request($path_info)) !== false) {
        $result = $back;
        break;
      }
    }
    return $result;
  }
  
  public static function link($link_function, $params = null) {
    if(count($args = func_get_args()) > 2) {
      $params = $args;
    } else if($params !== null && !is_array($params)) {
      $params = array($params);
    }
    
    if(isset($this->routes[$link_function])) {
      return user_call_func_array(Array($this->routes[$link_function], 'link'), $params);
    } else {
      throw new InvalidArgumentException("the link function '$link_function' doesn't exist!");
    }
  }
  
  public static function __callStatic($method, $args) {
    if(substr($method, 0, 5) === "link_") {
      return self::link(substr($method, 5), $args);
    } else if(substr($method, 0, 9) === "redirect_") {
      return self::redirect(substr($method, 9), $args);
    } else {
      throw new BadMethodCallException("no such method '$method'");
    }
  }


  /**
   * redirects to the given $controller and $action with the given $params
   *
   * @param mixed $controller
   * @param string $action
   * @param Array $params
   * @return string
   */
  public static function redirect($link_function, $params = null) {
    header("Location: " . call_user_func_array(Array(__CLASS__, 'link'), func_get_args()));
    exit;
  }
}

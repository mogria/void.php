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
      return self::$instance->getRouteList();
  }
  
  public function getRouteList() {
    return $this->routes;
  }

  public static function hasRoute($link_function) {
    $routes = self::getRoutes();
    return isset($routes[$link_function]);
  }

  public static function getRoute($link_function) {
    $routes = self::getRoutes();
    if(!isset($routes[$link_function])) {
      throw new InvalidArgumentException("tried to get inexistent route (via link_function '$link_function')");
    }
    return $routes[$link_function];
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
    // check if it is a garbage link or an absolute link
    if($link_function === '' || $link_function === '#' || preg_match('/^[a-z\+\-]{2,}:\/\//', $link_function)) {
      return $link_function;
    } else {
      // no? let's create a link

      // some params given?
      if(($count = count($args = func_get_args())) > 2) {
        $params = $args;
      } else if($count === 2 && !is_array($params)) {
        $params = array($params);
      } else if($count === 1) {
        $params = array();
      }
      
      // route available that handles $link_function ?
      if(self::hasRoute($link_function)) {
        // yup. let's call the link method of the route
        $path = call_user_func_array(Array(self::getRoute($link_function), 'link'), $params);

        // got the root path? return BASEURL
        if($path === "/") {
          return BASEURL;
        } else {
          // else prepend BASEURL
          return BASEURL . self::getIndexFile() . $path;
        }
      } else {
        throw new InvalidArgumentException("the link function '$link_function' doesn't exist!");
      }
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

  public static function __callStatic($method, $args) {
    if(substr($method, 0, 5) === "link_") {
      return self::link(substr($method, 5), $args);
    } else if(substr($method, 0, 9) === "redirect_") {
      return self::redirect(substr($method, 9), $args);
    } else {
      throw new BadMethodCallException("no such method '$method'");
    }
  }

  public static function getIndexFile() {
    return self::$config->index_file;
  }
}

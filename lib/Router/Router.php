<?php

namespace Void;
use \InvalidArgumentException;
use \BadMethodCallException;

/**
 * This class generates links, redirects and is responsible to store
 * all routes.
 *
 * @author Mogria
 * @package void.php
 */
class Router extends VoidBase {
    
  protected $routes = Array();
  
  /* singleton */
  protected static $instance;
  
  protected function __construct() {}
  private function __clone() {}
  
  /**
   * adds a route.
   * generates an link_function if none is given (out of $url).
   * throws an exception if the link_function isn't unique
   *
   * @param string $url
   * @param mixed $target
   * @param string $link_function
   *
   * @return void
   */
  public function match($url, $target, $link_function = null) {
    $link_function = self::generateLinkFunction(is_string($link_function) ? $link_function : $url);
    if(isset($this->routes[$link_function])) {
      throw new InvalidArgumentException("the link function '$link_function' already exists!");
    }
    $this->routes[$link_function] = new Route($url, $target);
  }
  
  /**
   * creates a valid link function out of an string. it removes
   * all the special chars and replaces it by a _
   * all the _ at the start or at the end are stripped
   *
   * this function is used to create a link_function out of
   * the $url parameter in match()
   *
   * bsp.
   * /dafuq/@/random/string -> dafuq_random_string
   *
   * @static
   * @param string $url
   * @return string
   */
  public static function generateLinkFunction($url) {
    return trim(preg_replace('/_+/', '_', preg_replace('/[^a-zA-Z0-9]+/', '_', $url)), "_");
  }
  
  /**
   * calls the given $closure and passes an instance of itself as first parameter
   *
   * @static
   * @param Closure $closure
   * @return void
   */
  public static function configure($closure) {
    self::$instance = $route = new Router();
    $closure($route);
  }
  
  /**
   * get an array of Route objects
   *
   * @static
   * @return array
   */
  public static function getRoutes() {
    return self::$instance->getRouteList();
  }
  
  /**
   * get an array of Route objects
   *
   * @return array
   */
  public function getRouteList() {
    return $this->routes;
  }

  /**
   * check if a route exists with the link function $link_function
   *
   * @static
   * @param string $link_function
   * @return void
   */
  public static function hasRoute($link_function) {
    $routes = self::getRoutes();
    return isset($routes[$link_function]);
  }

  /**
   * get the route with the given link function $link_function
   *
   * @static
   * @param string $link_function
   * @return array
   */
  public static function getRoute($link_function) {
    $routes = self::getRoutes();
    if(!isset($routes[$link_function])) {
      throw new InvalidArgumentException("tried to get inexistent route (via link_function '$link_function')");
    }
    return $routes[$link_function];
  }

  /**
   * processes an request. checks if one of the route
   * matches the request and returns the stuff the which is
   * mapped to the request
   *
   * @static
   * @param string $path_info
   * @return string
   */
  public static function request($path_info) {
    $result = "";
    // iterate through all the routes (the routes added first have the highest priority)
    $routes = self::getRoutes();
    foreach($routes as $route) {
      // check if one of the route matches
      if(($back = $route->request($path_info)) !== false) {
        // if yes return the result
        $result = $back;
        break;
      }
    }
    return $result;
  }
  
  /**
   * creates an link using the link function $link_function with the params $params
   *
   * this calls the link()-method of one of the Route objects
   *
   * @static
   * @param string $link_function
   * @param array $params
   * @return void
   */
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
   * creates a link out of $link_function & $params using the link()-method and 
   * redirects to it
   *
   * @static
   * @param string $link_function
   * @param Array $params
   * @return string
   */
  public static function redirect($link_function, $params = null) {
    header("Location: " . call_user_func_array(Array(__CLASS__, 'link'), func_get_args()));
    exit;
  }

  /**
   * makes it possible to call inexistent methods and to make it easier to create
   * links and redirects
   *
   * throws an exception if the called method doesn't begin with link_ or redirect_
   *
   * examples:
   *
   * Router::link_root(); // -> /someurl/
   * Router::redirect_root(); // redirects you to /someurl/
   *
   * Router::link_user(15); // -> /someurl/index.php/user/show/15
   * Router::redirect_user(15); // redirects you to /someurl/index.php/user/show/15
   *
   * 
   * @static
   * @param string $method
   * @param array $args
   * @return mixed
   */
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
   * get the name of the index file (on apache mostly index.php)
   * you can configure this in config/environments and set it to a blank value
   * if you use mod_rewrite
   *
   * @static
   * @return string
   */
  public static function getIndexFile() {
    return self::$config->index_file;
  }
}

<?php

namespace Void;
use \InvalidArgumentException;

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
  
  private static function assoc_link_to_indexed(array $link) {
    $result = Array();
    if(isset($link['controller'])) {
      $result[] = $link['controller'];
      unset($link['controller']);
      if(isset($link['action'])) {
        $result[] = $link['action'];
        unset($link['action']);
        if(isset($link['params'])) {
          $result[] = $link['params'];
          unset($link['params']);
        }
      }
      
      $result = array_merge($result, $link);
    } else {
      $result = $link;
    }
    return $result;
  }
  /**
   * returns a link to the given $controller and $action with the given $params
   *
   * @param mixed $controller
   * @param string $action
   * @param Array $params
   * @return string
   */
  public static function link($controller = null, $action = null, Array $params = Array()) {
    
    // for notation like 'controller/action/param1'
    if($action === null && $params === Array()
      && (is_string($controller)
        // if $controller is an array of size 1, use the first element as $controller
        || (is_array($controller)
          && count($controller) === 1
          && $controller = array_shift($controller)))
      // check for valid characters
      && preg_match("/^[a-z0-9\\_\\/]*$/Di", $controller)
      // there has to be a "/" inside the string
      && strpos($controller, "/") !== false) {
      // split it by "/" and create an array out of it
      $controller = explode("/", $controller);
    }
      
    if (is_array($controller)) {
      $array = self::assoc_link_to_indexed($controller);
      // get the controller
      $controller =  array_shift($array);
      // grab the action
      $action =  array_shift($array);
      // get the params
      $params = array_values($array);

      if(count($params) == 1 && is_array($params[0])) {
        $params = array_values($params[0]);
      }
    }

    // handle the things quite diffrent if $controller couldn't be a valid Class name
    if(!preg_match("/^[a-z0-9\_]*$/Di", $controller)) {
      return $controller;
    }

    // return link to root if no controller, action or any param is given
    if($controller === null && $action === null && $params == null) {
      return BASEURL;
    } else if ($action === null &&  $params == null) { // if only controller is given
      return BASEURL . urlencode(self::$config->index_file) . "/" . urlencode($controller);
    } else {
      // get the default controller if needed
      $controller = $controller == null ? Dispatcher::getDefaultController() : $controller;
      // get the default action if needed
      $action = $action == null ? Dispatcher::getDefaultMethod() : $action;
      // append / in front of each element and implode all the elements together
      $paramstr = implode("", array_map(function(&$value) {
        return "/" . urlencode($value);
      }, $params));

      // build and return the URL
      return BASEURL . urlencode(self::$config->index_file) . "/" . urlencode($controller) . "/" . urlencode($action) . $paramstr;
    }
  }

  /**
   * creates a link to a CSS Asset
   *
   * @param $main_file - the relative path without extension, relative to the stylesheet directory
   * @param $params - Additional Params
   */
  public static function linkCSS($main_file, Array $params = Array()) {
    return self::linkAsset("CSS", $main_file, $params);
  }

  /**
   * creates a link to a Javascript Asset
   *
   * @param $main_file - the relative path without extension, relative to the javascript directory
   * @param $params - Additional Params
   */
  public static function linkJS($main_file, Array $params = Array()) {
    return self::linkAsset("JS", $main_file, $params);
  }

  /**
   * creates a link to a Asset
   *
   * @param $type - the type of the asset ('CSS' or 'JS')
   * @param $main_file - the relative path without extension, relative to the directory where the assets of the given type are in
   * @param $params - Additional Params
   */
  protected static function linkAsset($type, $main_file, Array $params = Array()) {
    $main_file = explode("/", $main_file);
    $file = array_shift($main_file);
    $params = array_merge($main_file, $params);
    return self::link($type, $file, $params);
  }

  /**
   * redirects to the given $controller and $action with the given $params
   *
   * @param mixed $controller
   * @param string $action
   * @param Array $params
   * @return string
   */
  public static function redirect($controller = null, $action = null, Array $params = Array()) {
    header("Location: " . self::link($controller, $action, $params));
    exit;
  }

  public static function getIndexFile() {
    return self::$config->index_file;
  }
}

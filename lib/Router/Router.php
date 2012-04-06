<?php

namespace Void;

/**
 * This class generates links and redirects
 *
 * @author Mogria
 * @package void.php
 */
class Router extends VoidBase {
  /**
   * returns a link to the given $controller and $action with the given $params
   *
   * @param mixed $controller
   * @param string $action
   * @param Array $params
   * @return string
   */
  public static function link($controller = null, $action = null, Array $params = Array()) {
    // if it's an array pull out the required values
    if (is_array($controller)) {
      $array = $controller;
      $controller = isset($array['controller']) ? $array['controller'] : array_shift($array);
      $action = isset($array['action']) ? $array['action'] : array_shift($array);
      $params = array_diff_key($array, Array('controller' => null, 'action' => null));
    }

    // is it an URL like http://example.com? if yes return it;
    if(preg_match("/[a-z\-]{3,}:\/\//i", $controller)) {
      return $controller;
    }

    // return link to root if no controller, action or any param is given
    if($controller === null && $action === null && $params == null) {
      return BASEURL;
    } else if ($action === null &&  $params == null) { // if only controller is given
      return BASEURL . self::$config->index_file . "/" . $controller;
    } else {
      // get the default controller if needed
      $controller = $controller == null ? Dispatcher::DEFAULT_CONTROLLER : $controller;
      // get the default action if needed
      $action = $action == null ? Dispatcher::DEFAULT_METHOD : $action;
      // append / in front of each element and implode all the elements together
      $paramstr = implode("", array_map(function(&$value) {
        $value = "/" . $value;
      }, $params));

      // build and return the URL
      return BASEURL . self::$config->index_file . "/" . $controller . "/" . $action . $paramstr;
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
  public static function redirect($controller = null, $action = null, Array $params = Array()) {
    header("Location: " . self::link($controller, $action, $params));
    exit;
  }

  public static function getIndexFile() {
    return self::$config->index_file;
  }
}

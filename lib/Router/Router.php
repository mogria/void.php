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
      // get the controller
      $controller = isset($array['controller']) ? $array['controller'] : array_shift($array);
      // remove it from the array
      $array = array_diff_key($array, Array('controller' => null));
      // grab the action
      $action = isset($array['action']) ? $array['action'] : array_shift($array);
      // and remove it
      $array = array_diff_key($array, Array('action' => null));

      // get the params
      $params = array_values(isset($array['params']) ? $array['params'] : $array);

      if(count($params) == 1 && is_array($params[0])) {
        $params = $params[0];
      }
    }

    // is it an URL like http://example.com? if yes return it;
    if(preg_match("/^[a-z\-]{3,}:\/\//i", $controller)) {
      return $controller;
    }

    // return link to root if no controller, action or any param is given
    if($controller === null && $action === null && $params == null) {
      return BASEURL;
    } else if ($action === null &&  $params == null) { // if only controller is given
      return BASEURL . urlencode(self::$config->index_file) . "/" . urlencode($controller);
    } else {
      // get the default controller if needed
      $controller = $controller == null ? Dispatcher::DEFAULT_CONTROLLER : $controller;
      // get the default action if needed
      $action = $action == null ? Dispatcher::DEFAULT_METHOD : $action;
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
    return self::link("CSS", $main_file, $params);
  }

  /**
   * creates a link to a Javascript Asset
   *
   * @param $main_file - the relative path without extension, relative to the javascript directory
   * @param $params - Additional Params
   */
  public static function linkJS($main_file, Array $params = Array()) {
    return self::link("JS", $main_file, $params);
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

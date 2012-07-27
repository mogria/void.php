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

<?php

namespace Void;

/**
 * This represents an request, this class parses the passed url.
 * Example:
 *
 * Request: http://example.org/path_to_framework/index.php
 * $request->controller contains null
 * $request->method contains null
 * $request->params contains Array()
 *
 * Request: http://example.org/path_to_framework/index.php/test
 * $request->controller contains 'test'
 * $request->method contains null
 * $request->params contains Array()
 *
 * Request: http://example.org/path_to_framework/index.php/test/void
 * $request->controller contains 'test'
 * $request->method contains 'void'
 * $request->params contains Array()
 *
 * Request: http://example.org/path_to_framework/index.php/test/void/param/15
 * $request->controller contains 'test'
 * $request->method contains 'void'
 * $request->params contains Array('param', '15')
 *
 * @author Mogria
 * @package Void.php
 */
class Request extends VoidBase {

  /**
   * The elements of the URL
   * @var Array
   */
  protected $urlparams;

  /**
   * Constructor
   */
  public function __construct($urlparams = null, $resolve_request = true) {
    if($urlparams === null) {
      $urlparams = self::getPathInfo();
    }

    is_array($urlparams) && $urlparams = implode("/", $urlparams);
    $this->urlparams = self::requestStringToArray($resolve_request ? Router::request(urldecode($urlparams)) : $urlparams);
  }

  /**
   * Parses the requested URL
   */
  function toArray() {
    return $this->urlparams;
  }

  private static function requestStringToArray($str) {
    return array_values(array_diff(explode("/", $str), Array('')));
  }

  private static function getPathInfo() {
    return "/" . trim(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : "", "/");
  }

  public function compareTo($str) {
    return implode("/", $this->urlparams) === trim($str, "/");
  }

  /**
   * gives some Virtual properties
   * $request->controller // always the first part of the url, if not present null
   * $request->method // always the second part of the url, if not present null
   * $request->params // the rest of the url, if not present an empty Array
   * @param string $key the requested Variable
   */
  public function __get($key){
    if ($key == 'controller') {
      return isset($this->urlparams[0]) ? $this->urlparams[0] : null;
    } else if($key == 'method') {
      return isset($this->urlparams[1]) ? $this->urlparams[1] : null;
    } else if($key == 'params') {
      return array_slice($this->urlparams, 2);
    } else {
      return null;
    }
  }
}

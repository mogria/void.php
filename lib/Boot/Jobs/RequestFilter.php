<?php

namespace Void;

/**
 * removes stupid stuff from the request variables
 */
class RequestFilter implements Job {

  static $done = false;

  /**
   * removes magic quotes & null bytes
   *
   * @access public
   * @return void
   */
  public function run() {
    if(!self::$done) {
      $callbacks = Array();

      // remove the null bytes
      $callbacks[] = function($value) {
        return str_replace("\000", '', $value);
      };

      // strip the slashes if magic_quotes is on.
      get_magic_quotes_gpc() && $callbacks[] = function($value) {
        return stripslashes($value);
      };

      // walk through all the request variables & execute the callbacks for each value
      $request_vars = Array(&$_GET, &$_POST, &$_REQUEST, &$_COOKIE);
      array_walk_recursive($request_vars, function(&$value) use ($callbacks) {
        foreach($callbacks as $callback) {
          $value = $callback($value);
        }
      });
      self::$done = true;
    }
  }

  public function cleanup() {}
}

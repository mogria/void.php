<?php

namespace Void;

/**
 * This class removes all the slashes added by magic_quotes
 */
class MagicQuoteFix implements Job {

  /**
   * remove all the slashes from $_POST, $_GET, $_COOKIE & $_REQUEST if magic_quotes is on
   */
  public function run() {
    $vars = Array(&$_POST, &$_GET, &$_COOKIE, &$_REQUEST);
    if (get_magic_quotes_gpc()) {
      foreach($vars as &$var) {
        array_walk_recursive($var, array($this, 'fix_magic_quotes_walk'));
      }
    }
  }
  
  /**
   * A callback function needed by the run()-Method
   * This function simply strips slashes of the first given param
   */
  private function fix_magic_quotes_walk(&$value, $key) {
    $value = stripslashes($value);
  }
  
  /**
   * not this time.
   */
  public function cleanup() {}
}
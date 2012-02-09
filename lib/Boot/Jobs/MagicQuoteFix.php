<?php

namespace Void;

class MagicQuoteFix implements Job {
  public function run() {
    $vars = Array(&$_POST, &$_GET, &$_COOKIE, &$_REQUEST);
    if (get_magic_quotes_gpc()) {
      foreach($vars as &$var) {
        array_walk_recursive($var, array($this, 'fix_magic_quotes_walk'));
      }
    }
  }
  
  private function fix_magic_quotes_walk(&$value, $key) {
    $value = stripslashes($value);
  }
          
  public function cleanup() {}
}
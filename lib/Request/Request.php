<?php

namespace Void;

class Request {
  
  protected $urlparams;
  
  public function __construct() {
    $this->urlparams = self::getArray();
  }
  
  protected static function getArray() {
    return array_values(array_diff(explode("/", trim(str_replace($_SERVER['SCRIPT_NAME'], "", $_SERVER['PHP_SELF']), "/")), Array('')));
  }

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

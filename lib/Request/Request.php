<?php

namespace Void;

class Request {
  
  protected $urlparams;
  
  public static function grab() {
    $this->urlparams = self::getArray();
  }
  
  protected static function getArray() {
    return array_values(array_diff(explode("/", trim(str_replace($_SERVER['SCRIPT_NAME'], "", $_SERVER['PHP_SELF']), "/")), Array('')));
  }

  public function __get($key){
    if ($key == 'controller') {
      return $this->urlparams[0];
    } else if($key == 'method') {
      return $this->urlparams[1];
    } else if($key == 'params') {
      return array_slice($this->urlparams, 2);
    } else {
      return null;
    }
  }
}

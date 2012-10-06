<?php

namespace Void;

class RouteLoader extends VoidBase implements Job {
  public function run() {
    $file = self::$config->file;
    if(is_file($file) && is_readable($file)) {
      require_once self::$config->file;
    } else {
      throw new VoidException('could not load routes file');
    } 
  }

  public function cleanup() {
  }
}



<?php

namespace Void;

class ErrorToException implements Job {
  
  public function run() {
    set_error_handler(Array(__CLASS__, "exception_error_handler"));
  }
  
  public function cleanup() {}

  public static function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
  }

}

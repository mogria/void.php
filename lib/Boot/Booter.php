<?php

namespace Void;

class Booter extends JobCollection {
  static protected $instance;
  
  protected function __construct() {
    $this->add(new ErrorToException());
    $this->add(new MagicQuoteFix());
  }
  
  protected function __clone() {}
  
  public static function boot() {
    if(self::$instance instanceof Booter) {
      throw new \BadMethodCallException("already booted!");
    }
    self::$instance = new Booter();
    self::$instance->run();
  }
  
  public static function shutdown() {
    if(!self::$instance instanceof Booter) {
      throw new \BadMethodCallException("You need to " . __CLASS__ . "::boot() before you can call " . __METHOD__);
    }
    self::$instance->cleanup();
    self::$instance = null;
  }
}
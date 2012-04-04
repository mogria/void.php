<?php

namespace Void;

/**
 * This Boot's up the Applications and executes some Jobs which need to be executed
 */
class Booter extends JobCollection {
  /**
   * @var Booter contains an instance of the Booter if application is booted up
   */
  static protected $instance;
  
  /**
   * Adds some Jobs to the list
   * Only this class can create instances of this class
   */
  protected function __construct() {
    $this->add(new ErrorToException());
    $this->add(new MagicQuoteFix());
    $this->add(new PHPErrorMessages());
  }
  
  /**
   * Don't let someone clone this shit.
   */
  protected function __clone() {}
  
  /**
   * this function boots the application up, this executes all the jobs added in __construct()
   */
  public static function boot() {
    if(self::$instance instanceof Booter) {
      throw new \BadMethodCallException("already booted!");
    }
    self::$instance = new Booter();
    self::$instance->run();
  }
  
  /**
   * This executes all the cleanup()-Methods of the jobs added in __construct()
   */
  public static function shutdown() {
    if(!self::$instance instanceof Booter) {
      throw new \BadMethodCallException("You need to " . __CLASS__ . "::boot() before you can call " . __METHOD__);
    }
    self::$instance->cleanup();
    self::$instance = null;
  }
}

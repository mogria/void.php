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
    $types = Array('classes', isset($_SERVER['SHELL']) ? 'classes_shell' : 'classes_web');
    foreach($types as $type) {
      foreach(self::$config->$type as $class) {
        $class = __NAMESPACE__ . "\\" . $class;
        $this->add(new $class());
      }
    }
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

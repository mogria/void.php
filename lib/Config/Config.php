<?php

namespace Void;



class Config extends VirtualAttribute {
  
  protected $environment = null;

  protected $production;

  protected $test;

  protected $development;

  public function __construct($environment, $mixed = null) {
    $this->setEnvironment($environment);
    if($this->getEnvironment() === null) {
      $this->setEnvironment(DEVELOPMENT);
    }
    $mixed !== null && $this->config($mixed);
  }

  public function setEnvironment($environment) {
    if(self::isEnvironment($environment)) {
      $this->environment = $environment;
      $this->setReference($this->$environment);
    }
  }

  public static function isEnvironment($environment) {
    return $environment === PRODUCTION || $environment === TEST || $environment === DEVELOPMENT;
  }

  public static function getCalledClass() {
    $backtrace = debug_backtrace();
    $classname = __CLASS__;
    foreach($backtrace as $trace) {
      if(isset($trace['class']) && (isset($trace['object']) || $trace['type'] === "::")) {
        $class = $trace['class'];
        $object = isset($trace['object']) ? $trace['object'] : new \stdClass();
        if(!($class === __CLASS__ || ($object instanceof $classname))) {
          $class = explode("\\", $trace['class']);
          array_shift($class);
          $class = implode("\\", $class);
          return $class;
        }
      } else {
        return null;
      }
    }
  }

  // some functions to check on which environment we are
  public function getEnvironment() {
    return $this->environment;
  }

  public function onProduction() {
    return $this->getEnvironment() === PRODUCTION;
  }

  public function onTest() {
    return $this->getEnvironment() === TEST;
  }

  public function onDevelopment() {
    return $this->getEnvironment() === DEVELOPMENT;
  }

  /**
   * convert a classname to a key name
   */
  public static function convertClassName($classname) {
    return str_replace("\\", "_", strtolower($classname));
  }

  /**
   * concat multiple keys
   */
  public static function concatKeys($array) {
    if(!is_array($array)) {
      $array = func_get_args();
    }
    foreach($array as $key => $value) {
      if((string)$array[$key] === "") {
        unset($array[$key]);
      } 
    }
    return implode("_", $array);
  }

  /**
   * create a key name out of the classname which called and the key given
   */
  public static function classNameKey($key) {
    return self::concatKeys(self::convertClassName(self::getCalledClass()), $key);
  }

  // Override the base functions of VirtualAttribute and make the
  // keys depend on the class which calls the methods
  public function get($key) {
    // dont throw an exception if the key is not set before
    $back = null;
    try {
      $back = parent::get(self::classNameKey($key));
    } catch(UndefinedPropertyException $ex) {}
    return $back;
  }

  public function set($key, $value) {
    return parent::set(self::classNameKey($key), $value);
  }

  /**
   * Give a possibilty to configure this class via a closure (or an array)
   */
  public function config($mixed, $to_environment = null) {
    $environment = null;

    // change the environment if one is given
    if($to_environment !== null) {
      $environment = $this->getEnvironment();
      $this->setEnvironment($to_environment);
    }

    if($to_environment === 'all') {
      $this->config($mixed, DEVELOPMENT);
      $this->config($mixed, TEST);
      $this->config($mixed, PRODUCTION);
    // is it a array?
    } else if(is_array($mixed)) {
      $this->setArray($mixed);
    // is it callable?
    } else if(is_callable($mixed)) {
      $mixed($this);
    }

    // change back
    if($to_environment !== null) {
      $this->setEnvironment($environment);
    }
  }

}

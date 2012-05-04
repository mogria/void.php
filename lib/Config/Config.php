<?php

namespace Void;

/**
 * Config 
 * A Class which is responsible for storing all the configurations made
 * and create a simple interface for the classes to access the configured
 * values
 * 
 * @uses VirtualAttribute
 * @package Void.php
 */
class Config extends VirtualAttribute {
  
  /**
   * The current environment
   *
   * @access protected
   * @var mixed $environment
   */
  protected $environment = null;


  /*-- the following three attributes contain the configured values for all the three environments --*/

  /**
   * contains all the variables of the PRODUCTION environment
   *
   * @access protected
   * @var Array $production
   */
  protected $production = Array();

  /**
   * contains all the variables of the TEST environment
   * 
   * @var array
   * @access protected
   */
  protected $test = Array();

  /**
   * contains all the variables of the DEVELOPMENT environment
   * 
   * @var array
   * @access protected
   */
  protected $development = Array();

  /**
   * Constructor
   * defines the default environment and some default values
   * 
   * @access public
   * @param string $environment     the default environment
   * @param mixed $mixed            the initializer for this object
   *                                (can be a callback function or an array)
   * @see setEnvironment
   * @see config
   */
  public function __construct($environment, $mixed = null) {
    $this->setEnvironment($environment);
    if($this->getEnvironment() === null) {
      $this->setEnvironment(DEVELOPMENT);
    }
    $mixed !== null && $this->config($mixed);
  }

  /**
   * returns the current environment
   *
   * @access public
   * @return string
   * @see onProcuction
   * @see onTest
   * @see onDevelopment
   */
  public function getEnvironment() {
    return $this->environment;
  }

  /**
   * returns wheter we are on production or not
   * 
   * @access public
   * @return bool
   */
  public function onProduction() {
    return $this->getEnvironment() === PRODUCTION;
  }

  /**
   * returns wheter we are on the test environment or not
   * 
   * @access public
   * @return bool
   */
  public function onTest() {
    return $this->getEnvironment() === TEST;
  }

  /**
   * returns wheter we are on the development environment or not
   * 
   * @access public
   * @return void
   */
  public function onDevelopment() {
    return $this->getEnvironment() === DEVELOPMENT;
  }

  /**
   * swap to an other environment
   * (the config of the current environment _isn't_ lost
   *
   * @param string $environment
   * @return void
   * @see isEnvironment
   */
  public function setEnvironment($environment) {
    if(self::isEnvironment($environment)) {
      $this->environment = $environment;
      $this->setReference($this->$environment);
    }
  }

  /**
   * checks if the given value is a valid Environment
   * (DEVELOPMENT, TEST or PRODUCTION)
   * 
   * @param string $environment
   * @return bool
   */
  public static function isEnvironment($environment) {
    return $environment === PRODUCTION || $environment === TEST || $environment === DEVELOPMENT;
  }

  /**
   * follows the stack-trace and determines the class which called this object 
   * 
   * @static
   * @access public
   * @return string      The Classname
   */
  public static function getCalledClass() {
    // get the stack trace
    $backtrace = debug_backtrace();
    // get the name of this class
    $classname = __CLASS__;

    // iterate througth the trace
    foreach($backtrace as $trace) {
      // has the current point in the trace a class & an object? or called staticly?
      if(isset($trace['class']) && (isset($trace['object']) || $trace['type'] === "::")) {
        // get the class
        $class = $trace['class'];
        // get the object (if static simply an instance of stdClass)
        $object = isset($trace['object']) ? $trace['object'] : new \stdClass();
        // go to the next trace element if the class is the class itself
        // or it was a static call
        if(!($class === __CLASS__ || ($object instanceof $classname))) {
          // remove the first part of the namespace
          $class = explode("\\", $trace['class']);
          array_shift($class);
          $class = implode("\\", $class);
          // return the classname
          return $class;
        }
      } else {
        // no class found
        return null;
      }
    }
  }

  /**
   * convert a classname to a key name
   * 
   * @param string $classname 
   * @static
   * @access public
   * @return string
   */
  public static function convertClassName($classname) {
    // replace the backsplashes with underlindes and lowercase the name
    return str_replace("\\", "_", strtolower($classname));
  }

  /**
   * concat multiple keys
   *
   * you can pass the keys like this:
   * Config::concatKeys("key1", "key2", "key3");
   * or:
   * Config::concatKeys(array("key1", "key2", "key3"));
   * 
   * @param mixed $array 
   * @static
   * @access public
   * @return string
   */
  public static function concatKeys($array = "") {
    // if the first element isn't an array, create one out of all the given arguments
    if(!is_array($array)) {
      $array = func_get_args();
    }

    // drop empty keys
    foreach($array as $key => $value) {
      if((string)$array[$key] === "") {
        unset($array[$key]);
      } 
    }

    // implode and return the keys with "_"'s
    return implode("_", $array);
  }

  /**
   * create a key name out of the classname which called and the key given
   *
   * lets say we call this method from the class Test as follows:
   * Config::classNameKey("my_key");
   * the function would return
   * => "test_my_key"
   * 
   * @param string $key 
   * @static
   * @access public
   * @return string 
   * @see concatKeys
   * @see convertClassName
   * @see getCalledClass
   */
  public static function classNameKey($key) {
    return self::concatKeys(self::convertClassName(self::getCalledClass()), $key);
  }

  /**
   * This overrides the get() function of VirtualAttribute and make the
   * keys depend on the class which calls the methods
   * 
   * @param string $key 
   * @access public
   * @return mixed
   * @see set
   * @see getCalledClass
   */
  public function get($key, $to_environment = null) {
    $back = null;
    $environment = null;

    // change the environment if one is given
    if($to_environment !== null) {
      $environment = $this->getEnvironment();
      $this->setEnvironment($to_environment);
    }

    // dont throw an exception if the key is not set before
    try {
      // create the new classname and pass it to the function of the superclass
      $back = parent::get(self::classNameKey($key));
    } catch(UndefinedPropertyException $ex) {}

    // change back the environment back
    if($to_environment !== null) {
      $this->setEnvironment($environment);
    }

    return $back;
  }

  /**
   * This overrides the set() function of VirtualAttribute and make the
   * keys depend on the class which calls the methods
   * 
   * @param  string $key 
   * @param mixed $value 
   * @access public
   * @return void
   * @see get
   * @see getCalledClass
   */
  public function set($key, $value) {
    // also convert the key first if we are setting a value
    return parent::set(self::classNameKey($key), $value);
  }

  /**
   * possibilty to configure this class via a closure (or an array)
   *
   * Example of use:
   * $config_object->config(function($cfg) {
   *   $cfg->value = "asdasd";
   * }, 'all');
   * 
   * @param mixed $mixed             the closure or the array
   * @param string $to_environment   the environment in which the changes will be made
   *                                 or 'all' to make the changes on all envorironments. 
   * @access public
   * @return void
   * @see set
   * @see setEnvironment
   */
  public function config($mixed, $to_environment = null) {
    $environment = null;

    // change the environment if one is given
    if($to_environment !== null) {
      $environment = $this->getEnvironment();
      $this->setEnvironment($to_environment);
    }

    if($to_environment === 'all') {
      // call the method itself with all the environments
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

    // change back the environment back
    if($to_environment !== null) {
      $this->setEnvironment($environment);
    }
  }

  /**
   * prints to stdout in which environment we are and all the variables declared
   *
   * @access public
   * @return void
   */
  public function debugDump() {
    echo "\nENV: " . $this->getEnvironment() . "\n";
    print_r($this->{$this->getEnvironment()});
  }

}

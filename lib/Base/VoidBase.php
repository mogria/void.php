<?php

namespace Void;

/**
 * This is the base class of most of the classes in this framework
 *
 * This gives the classes the ability to get configured, because the $config
 * variable is shared to all subclasses
 */
abstract class VoidBase {

  /**
   * the config variable which contains an Config object with diffrent values in it
   *
   * @static
   * @access protected
   * @var Config $config
   */
  protected static $config;


  /** 
   * The Setter for the $config attribute
   *
   * @static
   * @access public
   * @param Config $config - the new Config object
   */
  public static function setConfig(Config $config) {
    static::$config = $config;
  }

  private static $route;

  public static function setRoutes($closure) {
    $route = new Route(Request::getPathLink());
    $closure($route);
    self::$route = $route;
  }

  public static function getRoutes() {
    return self::$route;
  }
}

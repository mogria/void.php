<?php

namespace Void;

class Request {
  
  protected static $request;
  
  public static function grab() {
    self::$request = self::getArray();
  }
  
  protected static function getArray() {
    return array_values(array_diff(explode("/", trim(str_replace($_SERVER['SCRIPT_NAME'], "", $_SERVER['PHP_SELF']), "/")), Array('')));
  }
  
  public static function getController() {
    return self::buildControllerName(isset(self::$request[0]) ?  self::$request[0] : self::getDefaultController());
  }
  
  public static function getMethod() {
    return self::buildMethodName(isset(self::$request[1]) ? self::$request[1] : self::getDefaultMethod());
  }
  
  public static function getParams() {
    return array_slice(self::$request, 2);
  }
  
  public static function getDefaultController() {
    return "Main";
  }
  
  public static function getDefaultControllerName() {
    return self::buildControllerName(self::getDefaultController());
  }
  
  public static function buildControllerName($name) {
    return __NAMESPACE__ . "\\" . ucfirst($name) . "Controller";
  }
  
  public static function getDefaultMethod() {
    return "index";
  }
  
  public static function getDefaultMethodName() {
    return self::buildMethodName(self::getDefaultMethod());
  }
  
  public static function buildMethodName($name) {
    return "action_" . strtolower($name);
  }
}

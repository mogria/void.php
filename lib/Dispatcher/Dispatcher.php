<?php

namespace Void;

class Dispatcher extends VoidBase {
  protected $request;

  public function __construct(Request $request) {
    $this->request = $request;
  }

  public function getController() {
    !class_exists($classname = $this->getControllerName()) && $classname = $this->getDefaultControllerName();
    return new $classname();
  }

  public function getAction($controller) {
    $methodname = $this->getMethod();
    $controller instanceof ControllerBase && !method_exists($controller, $methodname) && $methodname = self::getDefaultMethodName();
    return $methodname;
  }

  public function getActionName($controller) {
    return substr($this->getAction($controller), strlen(self::$config->method_prefix));
  }

  public function getControllerName() {
    return $this->buildControllerName($this->request->controller != null ? $this->request->controller: self::getDefaultController());
  }

  public function getMethod() {
    return $this->buildMethodName($this->request->method != null ? $this->request->method : self::getDefaultMethod());
  }

  public function getParams() {
    return $this->request->params;
  }

  public static function getDefaultController() {
    return self::$config->default_controller;
  }

  public function getDefaultControllerName() {
    return $this->buildControllerName(self::getDefaultController());
  }

  public function buildControllerName($name) {
    return __NAMESPACE__ . "\\" . ucfirst($name) . self::$config->controller_ext;
  }

  public static function getDefaultMethod() {
    return self::$config->default_method;
  }

  public function getDefaultMethodName() {
    return $this->buildMethodName(self::getDefaultMethod());
  }

  public function buildMethodName($name) {
    return self::$config->method_prefix . strtolower($name);
  }

  public static function getControllerExtension() {
    return self::$config->controller_ext;
  }


  public static function getMethodPrefix() {
    return self::$config->method_prefix;
  }
}

<?php

namespace Void;

class Dispatcher {
  protected $request;

  const DEFAULT_CONTROLLER = "Pages";
  const DEFAULT_METHOD     = "index";
  const CONTROLLER_EXT     = "Controller";
  const METHOD_PREFIX      = "action_";

  public function __construct(Request $request) {
    $this->request = $request;
  }

  public function getController() {
    !class_exists($classname = $this->getControllerName()) && $classname = $this->getDefaultControllerName();
    return new $classname();
  }

  public function getAction(ControllerBase $controller) {
    !method_exists($controller, $methodname = $this->getMethod()) && $methodname = $this->getDefaultMethodName();
    return $methodname;
  }

  public function getActionName(ControllerBase $controller) {
    return substr($this->getAction($controller), strlen(self::METHOD_PREFIX));
  }

  public function getControllerName() {
    return $this->buildControllerName($this->request->controller != null ? $this->request->controller: $this->getDefaultController());
  }

  public function getMethod() {
    return $this->buildMethodName($this->request->method != null ? $this->request->method : $this->getDefaultMethod());
  }

  public function getParams() {
    return $this->request->params;
  }

  public function getDefaultController() {
    return self::DEFAULT_CONTROLLER;
  }

  public function getDefaultControllerName() {
    return $this->buildControllerName($this->getDefaultController());
  }

  public function buildControllerName($name) {
    return __NAMESPACE__ . "\\" . ucfirst($name) . self::CONTROLLER_EXT;
  }

  public function getDefaultMethod() {
    return self::DEFAULT_METHOD;
  }

  public function getDefaultMethodName() {
    return $this->buildMethodName($this->getDefaultMethod());
  }

  public function buildMethodName($name) {
    return self::METHOD_PREFIX . strtolower($name);
  }
}

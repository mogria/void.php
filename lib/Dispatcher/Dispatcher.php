<?php

namespace Void;

class Dispatcher {  
  public static function getController() {
    !class_exists($classname = Request::getController()) && $classname = Request::getDefaultControllerName();
    return new $classname();
  }
  
  public static function executeAction(ControllerBase $controller) {
    !method_exists($controller, $methodname = Request::getMethod()) && $methodname = Request::getDefaultMethodName();
    return call_user_func_array(Array($controller, $methodname), Request::getParams());
  }
}
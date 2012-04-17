<?php

namespace Void;

/**
 * returns the name of controller & actions & params
 * according to the request
 */
class Dispatcher extends VoidBase {
  /**
   * the request object where the data comes from
   *
   * @access protected
   * @var Request $request
   */
  protected $request;

  /**
   * Constructor
   *
   * Initializes this object with a request object
   */
  public function __construct(Request $request) {
    $this->request = $request;
  }

  /**
   * returns an instance of the Contorller
   *
   * @access public
   * @return Controller
   */
  public function getController() {
    // does the controller exist? if not use the default one
    !class_exists($classname = $this->getControllerName()) &&
      $classname = $this->getDefaultControllerName();
    // create and instance of the controller and return it.
    return new $classname();
  }

  /**
   * if a $controller-object is this method returns the name of
   * the method which should be called if it exists
   * (else the default one is used (bsp. 'action_index'))
   *
   * if no $controller-object is given the method name will
   * be returned without checks
   *
   * @access public
   * @param Controller $controller
   * @return string - the name of the method
   */
  public function getAction($controller = null) {
    $methodname = $this->getMethod();
    $controller instanceof ControllerBase && !method_exists($controller, $methodname) &&
      $methodname = self::getDefaultMethodName();
    return $methodname;
  }

  /**
   * get the name of the action (without the prefix)
   *
   * @access public
   * @param Controller $controller (optional)
   * @return string
   */
  public function getActionName($controller = null) {
    return substr($this->getAction($controller), strlen(self::$config->method_prefix));
  }

  /**
   * returns the class name of the controller,
   * the default one if the one in the Request doesn't exists
   *
   * @access public
   * @return string 
   */
  public function getControllerName() {
    return self::buildControllerName(
      $this->request->controller != null
         ? $this->request->controller 
         : self::getDefaultController());
  }

  /**
   * returns the name of the Method,
   * the default one if the one in the Request is null
   *
   * @access public
   * @return string
   */
  public function getMethod() {
    return self::buildMethodName(
      $this->request->method != null
        ? $this->request->method
        : self::getDefaultMethod());
  }

  /**
   * returns an array if additional params given to the URL
   *
   * @access public
   * @return Array
   */
  public function getParams() {
    return $this->request->params;
  }

  /**
   * returns the name of the default Controller
   *
   * @access public
   * @static
   * @return string
   */
  public static function getDefaultController() {
    return self::$config->default_controller;
  }


  /**
   * returns the classname of the default Controller
   * 
   * @access public
   * @static
   * @return string
   */
  public static function getDefaultControllerName() {
    return self::buildControllerName(self::getDefaultController());
  }

  /**
   * builds a valid classname of a controller out of the $name
   *
   * @access public
   * @static
   * @param string $name
   * @return string
   */
  public static function buildControllerName($name) {
    return __NAMESPACE__ . "\\" . ucfirst($name) . self::$config->controller_ext;
  }

  /**
   * returns the name default method
   *
   * @access public
   * @static
   * @return string
   */
  public static function getDefaultMethod() {
    return self::$config->default_method;
  }

  /**
   * returns the full name of the default method
   *
   * @access public
   * @static
   * @return string
   */
  public static function getDefaultMethodName() {
    return self::buildMethodName(self::getDefaultMethod());
  }

  /**
   * builds a valid method name out of the $name
   *
   * @access public
   * @static
   * @param string $name
   * @return string
   */
  public static function buildMethodName($name) {
    return self::$config->method_prefix . strtolower($name);
  }

  /**
   * returns the extension which needs to be added to the
   * end of a name to become a valid Controller name
   *
   * @access public
   * @static
   * @return string
   */
  public static function getControllerExtension() {
    return self::$config->controller_ext;
  }


  /**
   * returns the prefix which needs to be added to a method
   * to become a valid method name
   *
   * @access public
   * @static
   * @return string
   */
  public static function getMethodPrefix() {
    return self::$config->method_prefix;
  }
}

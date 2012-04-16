<?php

namespace Void;

/**
 * The Controller from which every other controller should inherit.
 *
 */
abstract class ControllerBase extends VirtualAttribute {

  public function __construct() {}

  protected $view_vars;

  /**
   * Creates the view and calls a method from the controller
   * @param Dispatcher $dispatcher
   * @return string the rendered output of the view
   */
  public function executeAction(Dispatcher $dispatcher) {
    $this->setReference($this->view_vars);
  	$actionname = $dispatcher->getActionName($this);
    $controllername = explode("\\", get_called_class());
    $controllername = $controllername[count($controllername) - 1];
    $controllername = strtolower(substr($controllername, 0, -strlen(Dispatcher::getControllerExtension())));
    call_user_func_array(Array($this, Dispatcher::getMethodPrefix() . $actionname), $dispatcher->getParams());
    $layout = new Template(Array('layout', 'application'));
    $layout->setReference($this->getReference());
    $this->view = $layout->_content = new Template(Array(
      $controllername,
      $actionname
    ));
    $this->view->setReference($this->getReference());
  	return $layout->render();
  }

  abstract function action_index();
}

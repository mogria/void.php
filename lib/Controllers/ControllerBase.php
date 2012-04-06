<?php

namespace Void;

/**
 * The Controller from which every other controller should inherit.
 *
 */
abstract class ControllerBase extends VirtualAttribute {

  public function __construct() {}

  /**
   * Creates the view and calls a method from the controller
   * @param Dispatcher $dispatcher
   * @return string the rendered output of the view
   */
  public function executeAction(Dispatcher $dispatcher) {
  	$actionname = $dispatcher->getActionName($this);
    $controllername = explode("\\", get_called_class());
    $controllername = $controllername[count($controllername) - 1];
    $controllername = strtolower(substr($controllername, 0, -strlen(Dispatcher::getControllerExtension())));
  	$layout = new Template(Array('layout', 'application'));
    $this->view = $layout->content = new Template(Array(
      $controllername,
      $actionname
    ));
    $this->setReference($this->view->getReference());

  	call_user_func_array(Array($this, Dispatcher::getMethodPrefix() . $actionname), $dispatcher->getParams());
  	return $layout->render();
  }

  abstract function action_index();
}

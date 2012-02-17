<?php

namespace Void;

/**
 * The Controller from which every other controller should inherit.
 *
 */
abstract class ControllerBase {

  /**
   * Creates the view and calls a method from the controller
   * @param Dispatcher $dispatcher
   * @return string the rendered output of the view
   */
  public function executeAction(Dispatcher $dispatcher) {
  	$actionname = $dispatcher->getActionName($this);
  	$this->view = new Template(Array('application', 'layout'));

  	call_user_func_array(Array($this, Dispatcher::METHOD_PREFIX . $actionname), $dispatcher->getParams());
  	return $this->view->render();
  }

  abstract function action_index();
}

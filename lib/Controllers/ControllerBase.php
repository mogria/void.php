<?php

namespace Void;

abstract class ControllerBase {

  public function executeAction(Dispatcher $dispatcher) {
  	$actionname = $dispatcher->getActionName($this);
  	$this->view = new Template(Array('application', 'layout'));

  	call_user_func_array(Array($this, Dispatcher::METHOD_PREFIX . $actionname), $dispatcher->getParams());
  	return $this->view->render();
  }

  abstract function action_index();
}

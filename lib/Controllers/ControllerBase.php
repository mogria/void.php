<?php

namespace Void;

/**
 * The Controller from which every other controller should inherit.
 *
 */
abstract class ControllerBase extends VirtualAttribute {

  public function __construct() {}

  /**
   * Stores all the variables which get assigned to the view
   *
   * @var Array $view_vars
   * @acces protected
   */
  protected $view_vars;

  /**
   * Creates the view and calls a method from the controller
   * @param Dispatcher $dispatcher
   * @return string the rendered output of the view
   */
  public function executeAction(Dispatcher $dispatcher) {
    // use the property '$view_vars' to save all the variables for the view
    $this->setReference($this->view_vars);
    // get the name of the action called
  	$actionname = $dispatcher->getActionName($this);
    // grab the name of this controller
    $controllername = strtolower(substr(get_called_class(),
      ($pos = strrpos(get_called_class(), "\\")) === false ? 0 : $pos + 1,
      -strlen(Dispatcher::getControllerExtension())));

    $this->initialize();

    // call the action
    call_user_func_array(Array($this, Dispatcher::getMethodPrefix() . $actionname), $dispatcher->getParams());

    // create the template for the base layout
    $layout = new Template(Array('layout', 'application'));
    // also use '$view_vars' to store all the variables
    $layout->setReference($this->getReference());

    // create the template for this controller
    $this->view = $layout->_content = new Template(Array(
      $controllername,
      $actionname
    ));
    // also use '$view_vars' to store all the variables
    $this->view->setReference($this->getReference());

    // give access to the current controllername, the actionname and the given params
    $this->_action = $actionname;
    $this->_controller = $controllername;
    $this->_params = $dispatcher->getParams();

    // render the layout
  	return $layout->render();
  }

  abstract function action_index();
  abstract function initialize();
}

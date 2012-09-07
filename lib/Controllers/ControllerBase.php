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
   * Stores the subject which needs to be authenticated to access certain action
   * (may be an user object)
   * 
   * @var Authenticate
   * @access protected
   */
  protected $authenticate_subject = null;

  /**
   * helps to determine wheter the user should have access to a certain action.
   * to make use of this function you need to set the $__authenticate_subject property 
   * to an instance of Authentification
   *
   * if this condition is met this function will look for the static variable
   * $authenticate. this must be an array containing all the rights (as string) the
   * user should have to access ANY of the actions inside of the controller
   *
   * after that this function will look for a static variable named
   * authenticate_<current_action>. This must be an array containing all the rights
   * (as string) the user should have to access this specific action
   * (in this case <current_action>).
   *
   * @param $action - name of action which should be executed
   * @return bool - wheter the current user is allowed to access the specifed action
   */
  public function authenticate($action) {
    $ok = true;
    // check if the $__authenticate_subject variable is
    // set to an instance of Authenticate
    if(isset($this->authenticate_subject) && $this->authenticate_subject instanceof Authenticate) {
      // grab the role
      $role = $this->authenticate_subject->get_role();

      // array of rights the user should have
      $rights = array();

      // add the rights of the static $authenticate property
      if(isset(static::$authenticate)) {
        $rights = static::$authenticate;
      }
      // add the rights of the static $authenticate_<current_action> property
      $varname = "authenticate_$action";
      $rights = array_merge($rights, isset(static::${$varname}) ? static::${$varname} : array());

      // check if the user has all the specified rights
      foreach($rights as $right) {
        if(!$role->allowedTo($right)) {
          $ok = false;
          break;
        }
      }
    }
    return $ok;
  }

  /**
   * gets executed if the user tries to execute an action which
   * requires more permissions than he currently has.
   * creates a message flash with the message "insufficent permission rights"
   * and redirects to start page.
   *
   * if you don't like this behavior simply overwrite this method in one of your Controller or even in the ApplicationController.
   *
   */
  public function insufficent_permissions() {
    Flash::error("insufficent permission rights");
    return 'root';
  }

  /**
   * Creates the view and calls a method from the controller
   * @param Dispatcher $dispatcher
   * @return string the rendered output of the view
   */
  public function executeAction(Dispatcher $dispatcher, &$redirect) {
    // use the property '$view_vars' to save all the variables for the view
    $this->setReference($this->view_vars);
    // get the name of the action called
  	$actionname = $dispatcher->getActionName($this);
    // grab the name of this controller
    $controllername = substr(get_called_class(),
      ($pos = strrpos(get_called_class(), "\\")) === false ? 0 : $pos + 1,
      -strlen(Dispatcher::getControllerExtension()));

    // give the controller the ability to initialize his view variables etc.
    $this->initialize();

    // if authentification fails call insufficient_permissions trigger
    if(!$this->authenticate($actionname)) {
      $redirect = $this->insufficent_permissions();
      return null;
    }

    // call the action
    $back = call_user_func_array(Array($this, Dispatcher::getMethodPrefix() . $actionname), $dispatcher->getParams());
    if($back === null) {
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
    return null;
  }

  abstract function action_index();
  abstract function initialize();
}

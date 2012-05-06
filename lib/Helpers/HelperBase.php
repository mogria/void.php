<?php

namespace Void;

/**
 * All the Helper classes extend this class
 * All method calls will be forwarded to the template object
 */
class HelperBase extends VirtualAttribute {

  /**
   * @var Template
   * @access protected
   */
  protected $template;
  
  /**
   * Constructor
   *
   * @param Template $template
   */
  public function __construct(Template $template) {
    $this->template = $template;
    $this->setReference($this->template->getReference());
  }

  /**
   * Forwards all the method calls to the template object
   *
   * @param string $method - the name of the method to call
   * @param Array $args    - the arguments for the method call
   * @return mixed
   */
  public function __call($method, $args) {
    return $this->template->__call($method, $args);
  }
}

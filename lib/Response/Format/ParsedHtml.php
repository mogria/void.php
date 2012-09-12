<?php

namespace Void\Response\Format;
use Void\Response\Format;
use Void\Template;

class ParsedHtml implements Format {
  
  /**
   * the template file which should be rendererd
   * @var string
   */
  protected $template_file;

  /**
   * the layout file in which the template file gets wrapped
   * @var string
   */
  protected $layout_file;

  /**
   * the variables which are available in the template file
   * @var string
   */
  protected $variables;


  // @TODO: add getters & setters for $variables, $template_file & $layout_file

  /**
   * Constructor
   * initializes the properties
   *
   * @param &Array $variables - initializer for $variables property
   * @param &Array $template_file - initializer for $template_file property
   * @param &Array $layout_file - initializer for $layout_file property
   */
  public function __construct(&$variables, $template_file, $layout_file = null) {
    $this->variables = &$variables;
    $this->template_file = $template_file;
    $this->layout_file = $layout_file;
  }

  /**
   * returns the mime-type of an HTML document
   *
   * @return string
   */
  public function getMimeType() {
    return 'text/html';
  }

  /**
   * returns the file extension of an HTML document
   *
   * @return string
   */
  public function getFileExtenison() {
    return 'html';
  }


  /**
   * renders the templates and returns the result
   *
   * @return string
   */
  public function format() {
    // create the template for the current controller
    $back = $view = new Template($this->template_file);

    // also use '$variables' to store all the variables
    $view->setReference($this->variables);

    // if a layout file is given, place the view into the layout file
    if($this->layout_file != null) {
      // create the template for the base layout
      $layout = new Template($this->layout_file);

      // also use '$this->variables' to store all the variables
      $layout->setReference($this->variables);

      // nest the other template into the layout
      $layout->_content = $view;

      $back = $layout;
    }
    return $back->render();
  }
}

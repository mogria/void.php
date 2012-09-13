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
   * @var &Array
   */
  protected $variables;


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
   * getter for $template_file property
   * @return mixed
   */
  public function getTemplateFile() {
    return $this->template_file;
  }

  /**
   * setter for $template_file property
   * @param mixed $template_file
   */
  public function setTemplateFile($new_template_file) {
    $this->template_file = $new_template_file;
  }

  /**
   * getter for $layout_file property
   * @return mixed
   */
  public function getLayoutFile() {
    return $this->layout_file;
  }

  /**
   * setter for $layout_file property
   * @param mixed $layout_file
   */
  public function setLayoutFile($new_layout_file) {
    $this->layout_file = $new_layout_file;
  }

  /**
   * getter for $variables property
   * @return &Array
   */
  public function getVariables() {
    return $this->variables;
  }

  /**
   * setter for $variables property
   * @param &Array $variables
   */
  public function setVariables($new_variables) {
    $this->variables = $new_variables;
  }

  /**
   * returns the mime-type of an HTML document
   *
   * @return strin
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

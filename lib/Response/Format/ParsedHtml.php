<?php

namespace Void\Response\Format;
use Void\Response\Format;
use Void\Template;

class ParsedHtml implements Format {
  
  protected $template_file;
  protected $layout_file;
  protected $variables;


  public function __construct(&$variables, $template_file, $layout_file = null) {
    $this->variables = &$variables;
    $this->template_file = $template_file;
    $this->layout_file = $layout_file;
  }

  public function getMimeType() {
    return 'text/html';
  }

  public function getFileExtenison() {
    return 'html';
  }

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

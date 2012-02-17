<?php

/**
 * @author Mogria
 */

namespace Void;

class TemplateFinder {
  const TEMPLATE_DIR = "Views";
  const TEMPLATE_EXT = "tpl";

  protected $filespec;

  public function __construct($filespec) {
    $this->filespec = $filespec;
  }

  public function getPath() {
    if(is_array($this->filespec)) {
      $controller = isset($this->filespec['controller']) ? $this->filespec['controller'] : array_shift($this->filespec) ;
      $action = isset($this->filespec['action']) ? $this->filespec['action'] : array_shift($this->filespec);
      $file = ROOT . self::TEMPLATE_DIR . DS . $controller . DS . $action . "." . self::TEMPLATE_EXT;
    } else {
      $file = $this->filespec;    
    }
    if(!is_file($file)) {
      throw new InexistentFileException("Template File '$file' not found.");
    }
    return $file;
  }
}
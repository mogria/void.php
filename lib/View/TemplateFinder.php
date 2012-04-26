<?php

namespace Void;

/**
 * This class is responsible for finding Template files
 * @package Void.php
 * @author Mogria
 */
class TemplateFinder extends VoidBase {

  /**
   * An array or an string
   * @var mixed
   */
  protected $filespec;

  /**
   * Constructor
   *
   * @param mixed $filespec
   */
  public function __construct($filespec) {
    $this->filespec = $filespec;
  }

  /**
   * returns the path where the Template file is,
   * throws an InexistentFileException if none is found
   *
   * let's say in the TEMPLATE_DIR we have following files and directories
   *
   * |-test/
   * | |-index.tpl
   * | \-show.tpl
   * \-layout/
   *   |-index.tpl
   *   \-test.tpl
   *
   * if $filespec is Array('test', 'index') the file test/index.tpl is used
   * if $filespec is Array('action' => 'show', 'controller' => 'test') the file test/show.tpl is used
   * if $filespec is <path_to_template_directory>/layout/index.tpl, layout/index.tpl is used
   * if $filespec is layout/test, layout/test.tpl is used
   *
   * @return string
   */
  public function getPath() {
    if(is_array($this->filespec)) {
      $controller = isset($this->filespec['controller']) ? $this->filespec['controller'] : array_shift($this->filespec) ;
      $action = isset($this->filespec['action']) ? $this->filespec['action'] : array_shift($this->filespec);
      $file = $controller . DS . $action . "." . self::$config->ext;
    } else {
      $file = $this->filespec;
      // append the extension if not given
      !preg_match("/\\." . preg_quote(self::$config->ext) . "$/", $file)
        && $file .= "." . self::$config->ext;
    }

    // get the full path
    $file = ROOT . self::$config->dir . DS . $file;

    if(!is_file($file)) {
      throw new InexistentFileException("Template File '$file' not found.");
    }
    return $file;
  }
}

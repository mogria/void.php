<?php

namespace Void;

/**
 * This class is responsible for finding Template files
 * @package Void.php
 * @author Mogria
 */
class TemplateFinder {

  /**
   * The directory where all the Template files are in.
   * @var string
   */
  const TEMPLATE_DIR = "Views";

  /**
   * The extension the template files use.
   * @var string
   */
  const TEMPLATE_EXT = "tpl";

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
      $file = ROOT . self::TEMPLATE_DIR . DS . $controller . DS . $action . "." . self::TEMPLATE_EXT;
    } else {
      $file = $this->filespec;
    }
    if(!is_file($file) && !is_file($file = ROOT . self::TEMPLATE_DIR . DS . $file . self::TEMPLATE_EXT)) {
      throw new InexistentFileException("Template File '$file' not found.");
    }
    return $file;
  }
}
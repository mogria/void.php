<?php

namespace Void;

/**
 * This class is responsible for finding Template files
 * @package Void.php
 * @author Mogria
 */
class TemplateFinder extends VoidBase {

  protected $controller;

  protected $action;

  protected $file;

  public function getAction() {
    return $this->action;
  }

  public function getController() {
    return $this->controller;
  }

  /**
   * Constructor
   *
   * @param mixed $filespec
   */
  public function __construct($filespec = null) {
    $this->controller = "layout";
    $this->action = "application";
    $filespec !== null && $this->setFilespec($filespec);
  }

  /**
   * sets the path where the Template file is. (get it via getPath())
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
   * if $filespec is layout/test, layout/test.tpl is used
   *
   * @param mixed $filespec
   * @return string
   */
  public function setFilespec($new_filespec) {
    $file = $this->resolveFileSpec($new_filespec);

    // does the file exist?
    if(!is_file($file)) {
      throw new InexistentFileException("Template File '$file' not found.");
    }
    $this->file = $file;
  }

  /**
   * gets the controllername and the action name out of the mixed value $filespec and returns 
   * a path to a file
   *
   * @param mixed $filespec
   */
  private function resolveFileSpec($filespec) {
    if(is_string($filespec) && is_file($filespec)) {
      return $filespec;
    } else if(is_string($filespec) && is_file($filespec . "." . self::$config->ext)) {
      return $filespec . "." . self::$config->ext;
    }
    // filespec has to be an array || an string
    if(!is_array($filespec) && !is_string($filespec)) {
      throw new \InvalidArgumentException("Filespec has to be an array or an string");
    }

    // make $filespec to an array if it is a string by exploding the slashes
    if(is_string($filespec)) {
      $filespec = explode("/", $filespec);
    }

    // filter out the name of the controller
    $this->controller = isset($filespec['controller'])
                          ? $filespec['controller']
                          : (($element = array_shift($filespec)) == null
                            ? $this->controller
                            : $element);

    // filter out the name of the action
    $this->action = isset($filespec['action'])
                      ? $filespec['action']
                      : (($element = array_shift($filespec)) == null
                        ? $this->action
                        : $element);

    // build the path
    $file = ROOT . self::$config->dir . DS . s($this->controller)->uncamelize() . DS . $this->action;

    // append the extension if not given
    !preg_match("/\\." . preg_quote(self::$config->ext) . "$/", $file)
      && $file .= "." . self::$config->ext;


    // replace the slashes with the directory seperator of the current operating system.
    return str_replace(Array("///", "//", "/"), DS, $file);
  }

  /**
   * Returns the path to the template file
   * @return string 
   */
  public function getPath() {
    return $this->file;
  }
}

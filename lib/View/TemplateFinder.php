<?php

namespace Void;

/**
 * This class is responsible for finding Template files
 * @package Void.php
 * @author Mogria
 */
class TemplateFinder extends VoidBase {

  static protected $template_renderers = Array();

  protected $controller;

  protected $action;

  protected $file;

  protected $extension;

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

    if(self::$template_renderers == null) {
      $renderers = Array('VoidViewRenderer', 'PHPViewRenderer');
      foreach($renderers as $renderer) {
        $renderer = __NAMESPACE__ . "\\" . $renderer;
        $renderer = new $renderer();
        self::$template_renderers[$renderer->getExtension()] = $renderer;
      }
    }
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

    // filespec has to be an array || an string
    if(!is_array($new_filespec) && !is_string($new_filespec)) {
      throw new \InvalidArgumentException("Filespec has to be an array or an string");
    }

    // do we have any template renderers?
    if(count(self::$template_renderers) == 0) {
      throw new BadMethodCallException("no template renderers specified");
    }

    $new_filespec = $this->normalizeFileSpec($new_filespec);
    $file = $this->resolveFileSpec($new_filespec);

    // does the file exist?
    if($file === null || !is_file($file)) {
      throw new InexistentFileException("Template File '" . implode("/", $new_filespec) . "' not found.");
    }

    // set file property
    $this->file = $file;
  }

  /**
   * gets the controllername and the action name out of the mixed value $filespec and returns 
   * a path to a file or null if none has been found
   *
   * @param array $filespec
   * @return path to template file or null if none found
   */
  private function resolveFileSpec($filespec) {
    // normalize filespec remove unnecessary slashes and convert to array
    $extensions = array_keys(self::$template_renderers);

    // see if file was specified using the ['controller' => 'value', 'action' => 'index'] scheme
    if(count($filespec) == 2) {
      $keys = array_keys($filespec);
      $filespec = array_values($filespec);

      // check if 'controller' & 'action' have the correct position
      // in the array (because of array_values())
      if($keys[0] === 'action' || $keys[1] === 'controller') {
        $filespec = array($filespec[1], $filespec[0]);
      }
      $this->controller = $filespec[0] = (string)s($filespec[0])->uncamelize();
      $this->action = $filespec[1];
    }

    // convert filespec to path relative to Views/ directory
    $filespec = implode(DS, $filespec);

    // all extensions including an empty one
    $null_extensions = array_merge($extensions, array(''));

    // function to check if an extension matches
    $checkfunc = function($filespec, &$correct_extension) use($null_extensions) {
      foreach($null_extensions as $extension) {
        $tmpfilename = $filespec . ($extension !== "" ? "." . $extension : "");
        if(is_file($tmpfilename)) {
          $correct_extension = $extension;
          return $tmpfilename;
        }
      }
      return null;
    };
    
    // check if any extension matches in an relative or an absolute path
    $values = array($filespec, ROOT . self::$config->dir . DS . $filespec);
    foreach($values as $value) {
      if(($file = $checkfunc($value, $this->extension)) !== null) {
        $this->extension === '' && $this->extension = $extensions[0];
        return $file;
      }
    }

    // nothing matched, template not found
    return null;
  }

  /**
   * converts the $filespec to an array removing
   * empty values
   *
   * @param mixed $filespec
   * @return Array
   */
  private function normalizeFileSpec($filespec) {
    // convert $filespec to an array
    if(is_string($filespec)) {
      $filespec = str_replace("\\", "/", $filespec);
      $filespec = explode("/", $filespec);
    }

    // remove empty values, preserving the keys
    foreach($filespec as $key => $value) { 
      if(empty($value)) {
        unset($filespec[$key]);
      }
    }
    return $filespec;
  }

  /**
   * Returns the path to the template file
   * @return string 
   */
  public function getPath() {
    return $this->file;
  }

  /**
   * returns the view renderer for the file found
   * @return ViewRenderer
   */
  public function getRenderer() {
    $renderer = clone self::$template_renderers[$this->extension];
    $renderer->setFile($this->file);
    return $renderer;
  }
}

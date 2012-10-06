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
    $file = $this->resolveFileSpec($new_filespec);

    // does the file exist?
    if(!is_file($file)) {
      throw new InexistentFileException("Template File '$file' not found.");
    } else {

      $extensions = array_keys(self::$template_renderers);
      if(($pos = strrpos($this->action, ".")) !== false) {
        $extension = substr($this->action, $pos + 1);
        if(in_array($extension, $extensions)) {
          $this->extension = $extension;
        } else {
          $this->extension = $extensions[0];
        }
      } else {
        $this->extension = $extensions[0];
      }
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
    $extensions = array_keys(self::$template_renderers);

    if(is_string($filespec)) {
      $checkfunc = function($filespec) use($extensions) {
        $null_extensions = array_merge($extensions, array($extensions));
        foreach($null_extensions as $extension) {
          $tmpfilename = $filespec . ($extensions !== "" ? "." . $extension : "");
          if(is_file($tmpfilename)) {
            return $tmpfilename;
          }
        }
        return null;
      };
      
      $values = array($filespec, self::$config->dir . DS . $filespec);
      foreach($values as $value) {
        if(($ret = $checkfunc($value)) !== null) {
          return $ret;
        }
      }
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

    // do we have any template renderers?
    if(count($extensions) == 0) {
      throw new BadMethodCallException("no template renderers specified");
    }

    // per default use first extension in list
    $this->extension = $extensions[0];
    
    if(!is_file($file)) {
      $found = false;
      foreach($extensions as $extension) {
        $cur_file = $file . "." . $extension;
        if(is_file($cur_file)) {
          $file = $cur_file;
          $found = true;
          break;
        }
      }
    }

    // replace the slashes with the directory seperator of the current operating system.
    return $this->file = str_replace(Array("///", "//", "/"), DS, $file);
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

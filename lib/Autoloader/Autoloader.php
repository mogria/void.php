<?php

namespace Void;

use RecursiveDirectoryIterator;
use FilesystemIterator;
use RecursiveIteratorIterator;

/**
 * This class loads classes automaticly when the class is needed
 * To do so, this class creates an index. If a class is requested,
 * this class searches the index and includes the correct file.
 *
 * @package Void.php
 * @author Mogria
 */
class Autoloader {

  /**
   * @var Array $index
   */
  private static $index = Array();

  private static $index_dir;

  /**
   * Creates the index and stores it in self::$index
   *
   * @param string $dir Directory where the classes are
   */
  public static function init($dir) {
    self::$index_dir = $dir;
    !is_dir("tmp") && mkdir("tmp", 0777);

    clearstatcache();
    if(is_file("tmp/autoload-cache") && filemtime($dir) <= filemtime("tmp/autoload-cache")) {
      self::load_index();
    } else {
      self::recreate_index();
    }
  }

  public static function recreate_index() {
    self::$index = self::create_index(self::$index_dir);
    file_put_contents("tmp/autoload-cache", serialize(self::$index));
  }

  public static function load_index() {
    self::$index = unserialize(file_get_contents("tmp/autoload-cache"));
  }
  
  /**
   * This Method will be called if a class is requested.
   * The class will be included automaticly if the class
   * is in the index.
   *
   * @param string $classname The classname of the Class
   */
  public static function load($nclassname) {
    // don't let the index be recreated twice
    static $reloaded = false;

    // the class has to be in the Void namespace
    if(strpos($nclassname, __NAMESPACE__ . "\\") === 0) {
      $classname = substr($nclassname, ($lpos = strrpos($nclassname, "\\")) + 1);
      $namespace = trim(substr($nclassname, $fpos = strlen(__NAMESPACE__ . "\\"), $lpos - $fpos + 1), "\\");
      // is the class in the index ?
      if(isset(self::$index[$classname])
        && isset(self::$index[$classname][$namespace])
        && is_file(self::$index[$classname][$namespace])) {
        require_once self::$index[$classname][$namespace];
      }
    }
  }

  /**
   * A recursive function which creates the index
   *
   * @param string $dir The current directory
   * @return Array the index
   */
  private static function create_index($dir) {
    $list = Array();
    $files = $iterator = new PHPClassFileFilter(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)));
    foreach($files as $file) {
      $classname = substr($file->getFilename(), 0, strrpos($file->getFilename(), "."));
      if(!isset($list[$classname])) {
        $list[$classname] = Array();
        $list[$classname][''] = $file->getPathname();
      }

      $directory = dirname($file->getPathname());
      $libdir = realpath($dir) . DS . "lib";
      if(strpos($directory, $libdir) === 0) {
        $namespace = trim(substr($directory, strlen($libdir)), DS);
        $list[$classname][$namespace] = $file->getPathname();
      } else {
        $list[$classname][''] = $file->getPathname();
      }
    }
    return $list;
  }
}


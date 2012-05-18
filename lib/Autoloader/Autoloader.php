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
    !is_dir("tmp") && mkdir("tmp");
    if(is_file("tmp/autoload-cache")) {
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
  public static function load($classname) {
    // don't let the index be recreated twice
    static $reloaded = false;

    // the class has to be in the Void namespace
    if(strpos($classname, "Void\\") === 0) {
      // remove the Void\ prefix
      $classname = substr($classname, strlen("Void\\"));

      do {
        $again = false;

        // is the class in the index ?
        if(isset(self::$index[$classname]) && is_file(self::$index[$classname])) {
          require_once self::$index[$classname];
        } else if(!$reloaded) {
          $reloaded = true;
          self::recreate_index();
          $again = true;
        }
      } while($again);
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
      $list[$classname] = $file->getPathname();
    }
    return $list;
  }
}


<?php

namespace Void;

use RecursiveDirectoryIterator;
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

  /**
   * Creates the index and stores it in self::$index
   *
   * @param string $dir Directory where the classes are
   */
  public static function init($dir) {
    self::$index = self::create_index($dir);
  }

  /**
   * This Method will be called if a class is requested.
   * The class will be included automaticly if the class
   * is in the index.
   *
   * @param string $classname The classname of the Class
   */
  public static function load($classname) {
    if(strpos($classname, "Void\\") === 0) {
      $classname = substr($classname, strlen("Void\\"));
      if(isset(self::$index[$classname]) && is_file(self::$index[$classname])) {
        require_once self::$index[$classname];
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
    $files = $iterator = new PHPClassFileFilter(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)));
    foreach($files as $file) {
      $classname = substr($file->getFilename(), 0, strrpos($file->getFilename(), "."));
      $list[$classname] = $file->getPathname();
    }
    return $list;
  }
}


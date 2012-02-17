<?php

namespace Void;

// These filters are needed to filter the file's found
require_once ROOT . 'lib/Filters/Filter.php';
require_once ROOT . 'lib/Filters/FilterManager.php';
require_once ROOT . 'lib/Filters/ExtensionFilter.php';
require_once ROOT . 'lib/Filters/UcFirstFilter.php';

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
    self::$index = self::filter(self::create_index($dir));
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
    $dir = rtrim($dir, DS);
    $entries = scandir($dir);
    foreach($entries as $entry) {
      if($entry == "." || $entry == "..") {
        continue;
      }
      $entry = $dir . DS . $entry;
      if(is_dir($entry)) {
        $list = array_merge($list, self::create_index($entry));
      } else if(is_file($entry)) {
        $classname = basename($entry);
        $classname = substr($classname, 0, strrpos($classname, "."));
        $list[$classname] = $entry;
      }
    }
    return $list;
  }

  /**
   * This Method filters out all the classes which aren't
   * PHP Files containing a class.
   *
   * @param Array list The class index
   * @return Array The filtered index
   */
  private static function filter($list) {
    $manager = new FilterManager();
    $manager->add(new ExtensionFilter("php"));
    $list = $manager->filter($list);
    $list = array_flip($list);
    $manager = new FilterManager();
    $manager->add(new UcFirstFilter());
    return array_flip($manager->filter($list));
  }
}

// Create the index of all classes within this framework
Autoloader::init(ROOT);

// register the load() Method as an autoloader
spl_autoload_register(Array('Void\Autoloader', 'load'));
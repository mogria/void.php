<?php

namespace Void;

require_once ROOT . 'lib/Filters/Filter.php';
require_once ROOT . 'lib/Filters/FilterManager.php';
require_once ROOT . 'lib/Filters/ExtensionFilter.php';
require_once ROOT . 'lib/Filters/UcFirstFilter.php';

class Autoloader {
  
  public static $index = Array();
  
  public static function init($dir) {
    self::$index = self::filter(self::create_index($dir));
  }

  public static function load($classname) {
    if(strpos($classname, "Void\\") === 0) {
      $classname = substr($classname, strlen("Void\\"));
      if(isset(self::$index[$classname]) && is_file(self::$index[$classname])) {
        require_once self::$index[$classname];
      }
    }
  }

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

Autoloader::init(ROOT);

spl_autoload_register(Array('Void\Autoloader', 'load'));
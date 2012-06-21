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
   * Creates (or loads it from the cache) the index and stores it in self::$index
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

  /**
   * recreates (refreshes) the index and caches it in a file
   */
  public static function recreate_index() {
    self::create_index(self::$index_dir, self::$index);
    file_put_contents("tmp/autoload-cache", serialize(self::$index));
  }

  /**
   * loads the index from the cache
   */
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
        && is_file($filename = ROOT . DS . self::$index[$classname][$namespace])) {
        require_once $filename;
      }
    }
  }

  /**
   * A recursive function which creates the index
   * of all the PHP files containing a class
   *
   * @param string $dir
   * @param Array $list A reference in where the index is saved
   * @return Array the index
   */
  private static function create_index($dir, &$list) {
    // make $list to an array
    !is_array($list) && $list = Array();
    // open the directory
    if(false !== ($handle = opendir($dir))) {
      // remove tailing slashes
      $dir = rtrim($dir, DS);
      // remove  ./ in the front
      $dir = preg_replace("@^((\./)+)@", '', $dir);

      // read each directory entry
      while(false !== ($entry = readdir($handle))) {

        // create the full path
        $file = $dir . DS . $entry;
        if($entry === "." || $entry === "..") {
          // dont do something with ./ and ../
        } elseif (is_dir($file)) {
          // recursive call
          self::create_index($file, $list);
        } elseif (is_file($file) // only index file under the following conditions
          && ucfirst($entry) == $entry // first letter of filename has to be uppercase
          && ($position = strrpos($entry, ".php")) !== false // extension .php needed
          && strlen($entry) - strlen(".php") === $position 
          && !self::inExcludedDir($file)) { // the file mustn't be in an excluded directory
          $classname = substr($entry, 0, $position); // get classname out of the filename

          // initialize the key if it doesn't exist
          if(!isset($list[$classname])) {
            $list[$classname] = Array();
          }

          $libdir = "lib";
          if(strpos($dir, $libdir) === 0) {
            // detect the namespaces in where the class could be
            $namespace = trim(substr($dir, strlen($libdir)), DS);
            $namespace = str_replace("/", "\\", $namespace);
            $list[$classname][$namespace] = $file;
          } else {
            // the class is in no namespace
            $list[$classname][''] = $file;
          }
        }
      }
      // close the directory
      closedir($handle);
    }
  }

  /**
   * checks if the given file is in one of the excluded directories
   * e.g. (lib/Model) is included, ActiveRecord loads it's classes itself
   *
   *
   * @param $file - filename
   * @return bool if the given file is in one of the excluded directories
   */
  private static function inExcludedDir($file) {
    $inside = false;
    $file = str_replace("/", DS, $file);
    $excluded = Array("lib/Model");
    // iterate through each excluded directory
    foreach($excluded as $dir) {
      // replace all normal slashes in the path with the
      // directory separator (for windows compatibility)
      $dir = str_replace("/", DS, $dir);
      if(strpos($file, $dir) === 0) {
        // the file is in the directory $dir
        $inside = true;
        break;
      }
    }
    return $inside;
  }
}


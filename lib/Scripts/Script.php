<?php

namespace Void;
use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;

class Script extends VoidBase {

  protected static $initialized = false;

  protected static $own_instance;

  protected static $scripts = Array();

  const SCRIPT_DIR = "scripts/";

  private function __construct() {}
  private function __clone() {}

  public function __destruct() {
    self::cleanup();
  }

  public static function init() {
    if(!self::$initialized) {
      Booter::boot();
      self::$scripts = self::scan_scripts();
      self::$own_instance = new Script();
      self::$initialized = true;
    }
  }

  public static function parse($shortopts, $longopts = Array()) {
    $options = str_split(str_replace(':', '', $shortopts), 1);
    foreach($longopts as $opt) {
      $options[] = trim($opt, ":");
    }
    $options = array_fill_keys($options, null);
    return array_merge($options, getopt($shortopts, $longopts));
  }

  public static function scan_scripts() {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(self::SCRIPT_DIR));
    $result = Array();
    foreach($files as $file) {
      if($file->isFile()) {
        $result[] = (string)$file;
      }
    }
    return $result;
  }

  public static function call($scriptname) {
    if(!defined('VOIDPHP_SCRIPT')) {
      define('VOIDPHP_SCRIPT', 1);
    }
    $fullname = "scripts/$scriptname";
    if(in_array(self::$scripts, $fullname)) {
      $argc = count($argv);
      include $fullname;
      $args = array_slice(func_get_args(), 1);
      if(count($args) === 1 && is_array($args[0])) {
        $args = $args[0];
      }
      $closure = function($__script, $__args) {
        return call_user_func_array("{$__script}_main",  $__args);
      };
      return $closure($scriptname, $args);
    } else {
      throw new InvalidArgumentException("Script '$fullname' does not exist.");
    }
  }

  public static function cleanup() {
    if(self::$initialized) {
      self::$initialized = false;
    }
  }
}

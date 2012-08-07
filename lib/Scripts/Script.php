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
      self::$own_instance = new Script();
      self::$initialized = true;
    }
  }

  public static function parse($argv, $assigns) {
  }

  public static function scan_scripts() {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(self::SCRIPT_DIR));
    foreach($files as $file) {
      if($file->isFile()) {
        self::$scripts[] = (string)$file;
      }
    }
  }

  public static function call($_scriptname, $argv) {
    $_scriptname = "scripts/$_scriptname";
    if(in_array(self::$scripts, $_scriptname)) {
      $argc = count($argv);
      include $_scriptname;
    } else {
      throw new InvalidArgumentException("Script '$_scriptname' does not exist.");
    }
  }

  public static function cleanup() {
    if(self::$initialized) {
      self::$initialized = false;
    }
  }
}

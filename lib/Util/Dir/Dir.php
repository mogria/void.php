<?php

namespace Void;

class Dir {
  public static function remove($dir) {
    $ok = true;
    $dir = rtrim($dir, DS);
    $dirs = array_diff(scandir($dir), array(".", ".."));
    foreach($dirs as $entries) {
      $filename = $dir . DS . $entries;
      if(is_dir($filename)) {
        !self::remove($filename) && $ok = false;
      } else {
        !@unlink($filename) && $ok = false;
      }
    }
    !@rmdir($dir) && $ok = false;
    return $ok;
  }
}

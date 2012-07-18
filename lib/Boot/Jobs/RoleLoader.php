<?php

namespace Void;

/**
 * This loads the roles
 */
class RoleLoader extends VoidBase implements Job {
  
  public function run() {
    $extension = "Role.php";
    $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(self::$config->dir));
    foreach($files as $file) {
      if($file->isFile() && substr($file->getFilename(), -strlen($extension)) === $extension) {
        class_exists(substr($file->getFilename(), -3));
      }
    }
  }

  public function cleanup() {}

}

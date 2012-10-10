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
        $classname = substr($file->getFilename(), 0, -4);
        $classname = __NAMESPACE__ . "\\" . $classname;
        if(class_exists($classname)) {
          new $classname;
        }
      }
    }
  }

  public function cleanup() {}

}

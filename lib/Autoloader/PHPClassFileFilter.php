<?php

namespace Void;

use FilterIterator;

class PHPClassFileFilter extends FilterIterator {
  protected $excluded_dirs = Array(
    "lib/Model"
  );
  public function accept() {
    $ext = ".php";
    $value = $this->getInnerIterator()->current();
    $file = $value->getFilename();
    return $value->isFile()
      && ucfirst($file) == $file
      && ($position = strrpos($file, $ext)) !== false
      && strlen($file) - strlen($ext) == $position
      && !$this->inExcludedDir($value->getPath());
      ;
  }

  public function inExcludedDir($file) {
    $inside = false;
    foreach($this->excluded_dirs as $dir) {
      while($file != DS && $file != "." && !$inside) {
        // echo str_pad($file, 80, " ", STR_PAD_RIGHT) . ": " . str_pad(substr($file, -strlen($dir)), strlen($dir), " ", STR_PAD_RIGHT) . " === " . $dir . "\n";
        if(substr($file, -strlen($dir)) === $dir) {
          $inside = true;
        }
        $file = dirname($file);
      }
    }
    return $inside;
  }
}

<?php

namespace Void;

use FilterIterator;

class PHPClassFileFilter extends FilterIterator {
  public function accept() {
    $ext = ".php";
    $value = $this->getInnerIterator()->current();
    $file = $value->getFilename();
    return $value->isFile()
      && ucfirst(basename($file)) == basename($file)
      && ($position = strrpos($file, $ext)) !== false
      && strlen($file) - strlen($ext) == $position
      ;
  }
}
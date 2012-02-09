<?php

namespace Void;

class ExtensionFilter implements Filter {
  protected $extension;
    
  public function __construct($extension) {
    $this->extension = $extension;
  }
  
  public function filter($value) {
    return ($pos = strrpos($value, ".")) !== false && 
           substr($value, $pos + 1) === $this->extension;
  }
}
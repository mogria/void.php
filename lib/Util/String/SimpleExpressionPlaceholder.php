<?php

namespace Void;

class SimpleExpressionPlaceholder {

  protected $name;

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function __construct($name, $placeholder) {
  }
}

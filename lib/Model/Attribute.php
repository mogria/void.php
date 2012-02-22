<?php

namespace Void;

class Attribute {

  protected $value;

  protected $column;

  public function getColumn() {
    return $this->column;
  }

  public function getValue() {
    return $this->value;
  }

  public function setValue($value) {
    $this->value = $value;
  }

  public function __construct(Column $column, $value = null) {
    $this->column = $column;
    $this->setValue($value);
  }
}

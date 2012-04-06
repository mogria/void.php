<?php

namespace Void;

class Attribute {

  protected $column;
  protected $value;

  public function __construct(Column $column) {
    $this->column = $column;
    $this->setValue(null);
  }

  public function getValue() {
    return $this->value;
  }

  public function setValue($value) {
    $this->value = $value;
  }

  public function getColumn() {
    return $this->column;
  }

  public function getName() {
    $this->column->getName();
  }
}

<?php

namespace Void;

class Table extends Singleton {

  protected static $tables = Array();

  protected $columns;

  public function __construct($name) {
    $this->scan();
  }

  public static function getInstance($name) {
    !isset(self::$tables[$name]) && self::$tables[$name] = new Table($name);
    return self::$tables[$name];
  }

  public function scan() {

  }

  public function getColumn($name) {
    foreach($columns as $column) {
      if($column->getName() == $name) {
        return $column;
      }
    }
    return false;
  }

  public function addColumn(Column $column) {
    $this->columns[] = $column;
  }

  public function columnExists($name) {
    return $this->getColumn($name) !== false;
  }

  public function getAttributeList() {
    $attributes = Array();
    foreach($columns as $column) {
      $attributes[] = $column->getName();
    }
    return $attributes;
  }
}
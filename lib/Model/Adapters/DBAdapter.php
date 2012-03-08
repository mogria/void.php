<?php

namespace Void;

abstract class DBAdapter {
  protected $connection;
  protected $pdo;
  protected $types = Array(
    'string' => Array('UNDEFINED_TYPE'),
    'text' => Array('UNDEFINED_TYPE'),
    'integer' => Array('UNDEFINED_TYPE'),
    'float' => Array('UNDEFINED_TYPE'),
    'boolean' => Array('UNDEFINED_TYPE'),
    'binary' => Array('UNDEFINED_TYPE'),
    'date' => Array('UNDEFINED_TYPE'),
    'time' => Array('UNDEFINED_TYPE'),
    'timestamp' => Array('UNDEFINED_TYPE'),
    'datetime' => Array('UNDEFINED_TYPE'),
    'primary_key' => Array('UNDEFINED_TYPE')
  );

  public function __construct() {
    $this->connection = Connection::getInstance();
  }
  abstract public function quoteColumn(Column $column);
  abstract public function fullColumn(Column $column);

  public function convertType(Column $column) {
    $new_column = clone $column;
    if(isset($this->types[$column->type])) {
      $new_column->setType($types[$column->type][0]);
      isset($this->types[$column->type]['length']) && $new_column->setLength($this->types[$column->type]['length']);
    }
    return $new_column;
  }
}

<?php

namespace Void;

class Column {
  protected $name;
  protected $length;
  protected $type;
  protected $table;

  public function __construct(Table $table) {
    $this->table = $table;
  }

  public function getTable() {
    return $this->table;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function getLength() {
    return $this->length;
  }

  public function setLength($length) {
    $this->length = $length;
  }

  public function getType() {
    return $this->type;
  }

  public function setType($type) {
    $this->type = $type;
  }

  public function castType($type) {
    $php_types = Array(
      'string' => function($value) { return (string)$value; },
      'text' => function($value) { return (string)$value; },
      'integer' => function($value) { return (int)$value; },
      'float' => function($value) { return (double)$value; },
      'boolean' => function($value) { return (bool)$value; },
      'binary' => function($value) { return (string)$value; }, // or leave it?
      'date' => function($value) { return strtotime($value); },
      'time' => function($value) { return strtotime($value); },
      'timestamp' => function($value) { return strtotime($value); },
      'datetime' =>  function($value) { return strtotime($value); },
      'primary_key' => function($value) { return (int)$value; }
    );
    isset($php_types[$this->types]) && $type = $php_types[$this->types]($type);
    return $type;
  }

  public function getQuoted() {
    return Connection::getAdapter()->safeColumn($this);
  }

  public function getFull() {
    return Connection::getAdapter()->fullColumn($this);
  }

  public function getPDOType() {
    $pdo_types = Array(
      'string' => PDO::PARAM_STR,
      'text' => PDO::PARAM_STR,
      'integer' => PDO::PARAM_INT,
      'float' => PDO::PARAM_INT,
      'boolean' => PDO::PARAM_BOOL,
      'binary' => PDO::PARAM_LOB,
      'date' => PDO::PARAM_STR,
      'time' => PDO::PARAM_STR,
      'timestamp' => PDO::PARAM_INT,
      'datetime' => PDO::PARAM_STR,
      'primary_key' => PDO::PARAM_INT
    );

    if(isset($pdo_types[$this->type])) {
      return $pdo_types[$this->type];
    }
    return PDO::PARAM_STR;
  }
}

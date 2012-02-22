<?php

namespace Void;

class Model extends VirtualAttribtue {
  protected $connection;

  protected static $tablename = null;

  protected static $table = null;

  protected static $columns;

  protected $attributes;

  public function __construct(Array $attributes = Array()) {
    parent::__construct($this->attributes);
    self::$connection = Connection::getInstance();
    if(self::$tablename === null) {
      self::$tablename = self::modelToTable(get_called_class());
      self::$table = new Table(self::$tablename);
      $attributes = self::$table->getColumns();
    }
    foreach(self::$columns as $column) {
      $this->attributes[$column->getName()] = new Attribute($column, null);
    }
    $this->_ = $attributes;
  }

  public function set($key, $value) {
    $this->isUndefinedProperty();
    if(!$value instanceof Attribute) {
      $value = new Attribute(self::$table->getColumn($name), $value);
    }
    parent::set($key, $value);
  }

  public function exists($key) {
    return self::$table->columnExists($key);
  }

  public function delete() {}

  public static function modelToTable($modelname) {
    return strtolower($modelname);
  }

  public static function tableToModel($tablename) {
    return ucfirst(strtolower($tablename));
  }
}

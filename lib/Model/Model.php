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

  public function &get($key) {
    $this->isUndefinedProperty();
    return $this->__virtual_vars[$key]->getValue();
  }

  public function delete() {
    throw new BadMethodException("you cannot delete an attribute of an model");
  }

  public function exists($key) {
    return self::$table->columnExists($key);
  }

  public static function modelToTable($modelname) {
    return strtolower($modelname);
  }

  public static function tableToModel($tablename) {
    return ucfirst(strtolower($tablename));
  }

  public static function find($id) {
    $this->connection->prepare("SELECT * FROM $this->table WHERE id = ?", $id);
    // fetch this properly (fix the problem: this is a static method and $this->table and $this->connectionis needed ... )
  }
}

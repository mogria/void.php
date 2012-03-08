<?php

namespace Void;

class Model extends VirtualAttribtue {
  protected $connection;

  protected static $tablename = null;

  protected static $table = null;

  protected static $attributes;

  public function __construct(Array $attributes = Array()) {
    $this->connection = Connection::getInstance();
    $this->tablename === null && $this->tablename = self::modelToTable(get_called_class());
    $this->table === null && $this->table = new Table($name) && $this->attributes = $this->table->getAttributeList();
    $this->_ = $attributes;
  }

  public function set($key, $value) {
    $this->isUndefinedProperty();
    parent::set($key, $value);
  }

  public function exists() {
    return in_array($key, $this->attributes);
  }

  public static function modelToTable($modelname) {
    return strtolower($modelname);
  }

  public static function tableToModel($tablename) {
    return ucfirst(strtolower($tablename));
  }
}

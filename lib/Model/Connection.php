<?php

namespace Void;

use PDO;

class Connection extends Singleton {

  const DB_TYPE = 'mysql';
  const DB_HOST = 'localhost';
  const DB_NAME = 'void.php';
  const DB_USER = 'root';
  const DB_PASS = '';

  protected $pdo;
  protected $adapter;

  protected function __construct($type, $host, $name, $user = null, $pass = null) {
    $dsn = "$type:dbname=$name;host=$host";
    if($user == null) {
      $this->pdo = new PDO($dsn);
    } else if($pass == null) {
      $this->pdo = new PDO($dsn, $user);
    } else {
      $this->pdo = new PDO($dsn, $user, $pass);
    }
  }

  public static function getInstance() {
    self::$instance === null && self::$instance = new Connection(self::DB_TYPE, self::DB_HOST, self::DB_USER, self::DB_PASS);
    return self::$instance;
  }

  public function getPDO() {
    return $this->pdo;
  }

  private static function loadAdapter($type) {
    $classname = ucfirst(strtolower($type)) . "Adapter";
    if(!class_exists($classname)) {
      throw new UnsupportedDatabaseTypeException("Database Type '$type' is not supported. Class '$classname' not found.");
    }
    $this->adapter = new $classname;
  }

  public function getAdapter() {
    return $this->adapter;
  }

  /**
   * calls the Methods of the PDO objects if the called method doesn't exist on this object
   *
   * @param $method the called method name
   * @param $arguments the arguments given to the method call
   */
  public function __call($method, $arguments) {
    if(method_exists($this->pdo, $method)) {
      call_user_func_array(Array($this->pdo, $method), $arguments);
    } else {
      throw new BadMethodCallException("there is no method '$method'");
    }
  }
}

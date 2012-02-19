<?php

namespace Void;

use PDO;

class Connection extends Singleton {

  const DB_TYPE = 'mysql';
  const DB_HOST = 'localhost';
  const DB_NAME = 'void.php';
  const DB_USER = 'root';
  const DB_PASS = '';

  proteced $pdo;

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
}
<?php

namespace Void;

class MysqlAdapter extends DBAdapter {

  protected $types = Array(
    'string' => Array('VARCHAR', 'length' =>  225),
    'text' => Array('TEXT'),
    'integer' => Array('INTEGER', 'length' => 11),
    'float' => Array('FLOAT'),
    'boolean' => Array('BOOL'),
    'binary' => Array('BLOB'),
    'date' => Array('DATE'),
    'time' => Array('TIME'),
    'timestamp' => Array('DATETIME'),
    'datetime' => Array('DATETIME'),
    'primary_key' => Array('nt(11) UNSIGNED NOT NULL auto_increment PRIMARY KEY')
  );

  public function safeColumnName($column) {
    return "`" . $column . "`";
  }

  public function safeColumnTableName($table, $column) {
    return $this->safeTable($table) . "." . $this->safeColumn($column);
  }

  public function safeTable($table) {
    return "`" . $table. "`";
  }

  public function fullRow(Column $column) {
    $new_column = $this->convertType($column);
    return $this->safeColumn($column) . " " . $new_column->getType() . ($new_column->getLength() ? "(" . $new_column->getLength() . ")" : "");
  }

  public function getTables() {
    $tables = Array();
    $stmnt = $this->pdo->query("SHOW TABLES;");
    while($row = $stmnt->fetch(PDO::FETCH_NUM)) {
      $tables[] = $row[0];
    }
    return $tables;
  }

  public function getColumns($table) {
    $stmnt = $this->pdo->query('EXPLAIN ' . $this->safeTable($table) . ";");
  }
}
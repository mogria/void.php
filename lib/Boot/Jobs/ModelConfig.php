<?php

namespace Void;

/**
 * This configured ActiveRecord for void.php
 */
class ModelConfig extends VoidBase implements Job {
  
  public function run() {
    define('PHP_ACTIVERECORD_AUTOLOAD_DISABLE', 1);
    require_once ROOT . "lib" . DS . "Model" . DS . "ActiveRecord.php";
    \ActiveRecord\Config::initialize(function($cfg) {
      $cfg->set_model_directory(self::$config->dir);
      $cfg->set_connections(
        Array(
          DEVELOPMENT => self::$config->get('connection', DEVELOPMENT),
          TEST        => self::$config->get('connection', TEST),
          PRODUCTION  => self::$config->get('connection', PRODUCTION)
      ));
    });
  }
  public function cleanup() {}

}

<?php

namespace Void;

/**
 * This configured ActiveRecord for void.php
 */
class ModelConfig extends VoidBase implements Job {
  
  public function run() {
    define('PHP_ACTIVERECORD_AUTOLOAD_DISABLE', 1);
    require_once ROOT . "lib" . DS . "Model" . DS . "ActiveRecord.php";
    $config = self::$config;
    \ActiveRecord\Config::initialize(function($cfg) use ($config) {
      $cfg->set_model_directory($config->dir);
      $cfg->set_connections(
        Array(
          DEVELOPMENT => $config->get('connection', DEVELOPMENT),
          TEST        => $config->get('connection', TEST),
          PRODUCTION  => $config->get('connection', PRODUCTION)
      ));
    });
  }
  public function cleanup() {}

}

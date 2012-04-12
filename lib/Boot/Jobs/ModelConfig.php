<?php

namespace Void;

/**
 * This configured ActiveRecord for void.php
 */
class ModelConfig implements Job {
  
  public function run() {
    \ActiveRecord\Config::initialize(function($cfg) {
      $cfg->set_model_directory(self::$config->dir);
      $cfg->set_connections(
        Array(
          DEVELOPMENT => 'mysql://username:password@localhost/development_database_name',
          TEST        => 'mysql://username:password@localhost/development_database_name',
          PRODUCTION  => 'mysql://username:password@localhost/development_database_name'
      ));
    });
  }
  public function cleanup() {}

}

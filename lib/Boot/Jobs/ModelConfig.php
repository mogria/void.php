<?php

namespace Void;

/**
 * This configures ActiveRecord for void.php
 */
class ModelConfig extends VoidBase implements Job {
  
  /**
   * includes & configures ActiveRecord
   *
   * @access public
   * @return void
   */
  public function run() {
    // disable the ActiveRecord autoloader for Models
    define('PHP_ACTIVERECORD_AUTOLOAD_DISABLE', 1);

    // include ActiveRecord
    require_once ROOT . "lib" . DS . "Model" . DS . "ActiveRecord.php";

    // make a copy of the Config object for the following closure
    $config = self::$config;
    
    // initialize ActiveRecord
    \ActiveRecord\Config::initialize(function($cfg) use ($config) {
      // set the directory all the models are in
      $cfg->set_model_directory($config->dir);

      // set all the possible connections to the database
      $cfg->set_connections(
        Array(
          DEVELOPMENT => $config->get('connection', DEVELOPMENT),
          TEST        => $config->get('connection', TEST),
          PRODUCTION  => $config->get('connection', PRODUCTION)
      ));

      // set the default connection
      $cfg->set_default_connection($config->getEnvironment());
    });
  }

  public function cleanup() {}

}

<?php

namespace Void;

require_once dirname(__FILE__) . DS . 'default_configuration.php';


/* The current environment we are in.
 * Set the value to DEVELOPMENT, TEST or PRODUCTION
 *
 * There is a seperate Configration for each environment
 */
$environment = DEVELOPMENT;


/**
 * Returns the Configuration with ALL THE settings for each environment
 */
function getConfig() {
  // get the variable $environment from outside
  global $environment;

  // create the Config object and return it
  return new Config($environment, function($cfg) {
    
    // loading the default configuration into ALL the environments
    loadDefaultConfig($cfg);
    
    // the settings for all environments
    $cfg->config(function($cfg) {
      // the default Controller (if none is given)
      $cfg->dispatcher_default_controller = "Posts";
    }, 'all');

    // the settings just for the development environment
    $cfg->config(function($cfg) {
      /** database configuration **/
      $cfg->modelconfig_connection = 'mysql://root@localhost/voidphp_test_blog';
    }, DEVELOPMENT);


    // the settings for the test environment
    $cfg->config(function($cfg) {
      $cfg->modelconfig_connection = 'mysql://root@localhost/voidphp_test_blog';
    }, TEST);


    // the settings for the production environment
    $cfg->config(function($cfg) {
      $cfg->modelconfig_connection = 'mysql://root@localhost/voidphp_test_blog';
    }, PRODUCTION);
  });
}

$config = getConfig();
if(isset($overwrite_environment)) {
  $config->setEnvironment($overwrite_environment);
}
VoidBase::setConfig($config);

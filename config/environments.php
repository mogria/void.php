<?php

namespace Void;

require_once dirname(__FILE__) . DS . 'default_configuration.php';


/* The current environment we are in.
 * Set the value to DEVELOPMENT, TEST or PRODUCTION
 *
 * There is a seperate Configration for each environment
 */
$environment = DEVELOPMENT;


if(!function_exists('\Void\getConfig')) {
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
        $cfg->dispatcher_http_controller = "Http";
      }, 'all');

      // the settings just for the development environment
      $cfg->config(function($cfg) {
        /** database configuration **/
        $cfg->modelconfig_connection = 'mysql://root@localhost/voidphp_development';
      }, DEVELOPMENT);


      // the settings for the test environment
      $cfg->config(function($cfg) {
        $cfg->modelconfig_connection = 'mysql://root@localhost/voidphp_test';

        $cfg->sessioninit_models = Array('User');
      }, TEST);


      // the settings for the production environment
      $cfg->config(function($cfg) {
        $cfg->modelconfig_connection = 'mysql://root@localhost/voidphp_procution';
      }, PRODUCTION);
    });
  }
}

$config = getConfig();
if(isset($overwrite_environment)) {
  $config->setEnvironment($overwrite_environment);
}
VoidBase::setConfig($config);

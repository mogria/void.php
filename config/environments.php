<?php

namespace Void;

require dirname(__FILE__) . DS . 'default_configuration.php';


/* The current environment we are in.
 * Set the value to DEVELOPMENT, TEST or PRODUCTION
 *
 * There is a seperate Configration for each environment
 */
$environment = DEVELOPMENT;


/* Returns the Configuration with ALL THE settings for each environment
 * 
 *
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
      $cfg->dispatcher_default_controller = "Pages";
    }, 'all');

    // the settings just for the development environment
    $cfg->config(function($cfg) {
    }, DEVELOPMENT);


    // the settings for the test environment
    $cfg->config(function($cfg) {
    }, TEST);


    // the settings for the production environment
    $cfg->config(function($cfg) {
    }, PRODUCTION);
  });
}

VoidBase::setConfig(getConfig());

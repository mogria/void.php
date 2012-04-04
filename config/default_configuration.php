<?php

function loadDefaultConfig($cfg) {
  // default configuration for all the environments
  $cfg->config(function($cfg) {
    /** Template Finder */
    // in which folder the Views are stored (relative to the root)
    $cfg->templatefinder_dir = "Views";
    // the extension of the template files
    $cfg->templatefinder_ext = "tpl";

    /** Dispatcher */
    // all controllers have to have this suffix
    $cfg->dispatcher_controller_ext = "Controller";
    // all actions have to have this prefix
    $cfg->dispatcher_method_prefix = "action_";
    // the default action if none is given
    $cfg->dispatcher_default_method = "index";

    /** Router */
    $cfg->router_index_file = "index.php";
  }, 'all');

  // default configuration for the development environment
  $cfg->config(function($cfg) {
    $cfg->phperrormessages_on = true;
    $cfg->phperrormessages_level = E_ALL | E_STRICT;
  }, DEVELOPMENT);

  // default configuration for the test environment
  $cfg->config(function($cfg) {
    $cfg->phperrormessages_on = true;
    $cfg->phperrormessages_level = E_ALL;
  }, TEST);

  // default configuration for the test environment
  $cfg->config(function($cfg) {
    $cfg->phperrormessages_on = false;
    $cfg->phperrormessages_level = 0;
  }, PRODUCTION);
}

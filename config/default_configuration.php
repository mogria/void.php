<?php

function loadDefaultConfig($cfg) {
  // default configuration for all the environments
  $cfg->config(function($cfg) {
    /** Template Finder */
    // in which folder the Views are stored (relative to the root)
    $cfg->templatefinder_dir = "Views";
    // the extension of the template files
    $cfg->templatefinder_ext = "tpl";

    /** Template **/
    $cfg->template_helper_postfix = "Helper";

    /** Dispatcher */
    // all controllers have to have this suffix
    $cfg->dispatcher_controller_ext = "Controller";
    // all actions have to have this prefix
    $cfg->dispatcher_method_prefix = "action_";
    // the default action if none is given
    $cfg->dispatcher_default_method = "index";

    /** Routing */
    $cfg->routeloader_file = 'config/routes.php';
    $cfg->router_index_file = "index.php";

    /** Booter */
    $cfg->booter_classes = Array('ErrorToException', 'PHPErrorMessages', 'ModelConfig', 'UtilShortcuts', 'RoleLoader', 'RouteLoader');
    $cfg->booter_classes_web   = Array('RequestFilter', 'SessionInit');
    $cfg->booter_classes_shell = Array();

    /** Assets */
    $cfg->cssasset_dir = "stylesheets";
    $cfg->cssasset_ext = "css";
    $cfg->javascriptasset_dir = "javascripts";
    $cfg->javascriptasset_ext = "js";


    /** Model **/
    $cfg->modelconfig_dir = "Models";

    /** Cache **/
    $cfg->cache_dir = "tmp/cache";

    /** Hash **/
    $cfg->hash_iterations = 115;
    $cfg->hash_algo = 'whirlpool';
    $cfg->hash_secret = 'd9FbkL$[qI18G.Bl$g1 3_aGh,AuL.:tRoWq';

    /** Roles **/
    $cfg->roleloader_dir = "roles";
  }, 'all');

  // default configuration for the development environment
  $cfg->config(function($cfg) {
    $cfg->phperrormessages_on = true;
    $cfg->phperrormessages_level = E_ALL | E_STRICT;
  }, DEVELOPMENT);

  // default configuration for the test environment
  $cfg->config(function($cfg) {
    // directories
    $cfg->modelconfig_dir = "test/Models";
    $cfg->cssasset_dir = "test/stylesheets";
    $cfg->javascriptasset_dir = "test/javascripts";
    $cfg->templatefinder_dir = "test/Views";
    $cfg->roleloader_dir = "test/roles";
    $cfg->routeloader_file = "test/config/routes.php";

    $cfg->phperrormessages_on = true;
    $cfg->phperrormessages_level = E_ALL | E_STRICT;
  }, TEST);

  // default configuration for the production environment
  $cfg->config(function($cfg) {
    $cfg->phperrormessages_on = false;
    $cfg->phperrormessages_level = 0;
  }, PRODUCTION);
}

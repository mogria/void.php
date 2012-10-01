<?php

namespace Void;

chdir(__DIR__ . DIRECTORY_SEPARATOR . "..");

// require_once constants autoloader & routes
require_once 'config/consts.php';
require_once 'autoload.php';
require_once 'config/routes.php';



// boot in a test environment (this environment has a diffrent database connection)
$overwrite_environment = TEST;
require 'config/environments.php';
Booter::boot(true);

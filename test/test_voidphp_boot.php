<?php

namespace Void;

// boot in a test environment (this environment has a diffrent database connection)
$overwrite_environment = TEST;
require 'config/environments.php';
Booter::boot(true);

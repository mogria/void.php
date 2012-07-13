<?php

namespace Void;

// load the autoloader class
require_once dirname(__FILE__) . DS . 'lib/Autoloader/Autoloader.php';

// Create the index of all classes within this framework
Autoloader::init(".");

// register the load() Method as an autoloader
spl_autoload_register(Array('Void\Autoloader', 'load'));

<?php

namespace Void;

$dirname = dirname(__FILE__) . DS;

require_once $dirname . 'lib/Autoloader/PHPClassFileFilter.php';
require_once $dirname . 'lib/Autoloader/Autoloader.php';

// Create the index of all classes within this framework
Autoloader::init(ROOT);

// register the load() Method as an autoloader
spl_autoload_register(Array('Void\Autoloader', 'load'));
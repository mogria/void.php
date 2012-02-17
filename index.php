<?php

namespace Void;

require_once 'config/consts.php';
require_once 'lib/Autoloader/Autoloader.php';

Booter::boot();

$request = new Request();

$dispatcher = new Dispatcher($request);

$controller = $dispatcher->getController();

echo $controller->executeAction($dispatcher);

Booter::shutdown();
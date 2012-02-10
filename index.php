<?php

namespace Void;

require_once 'config/consts.php';
require_once 'lib/Autoloader/Autoloader.php';

Booter::boot();

Request::grab();

$controller = Dispatcher::getController();

echo Dispatcher::executeAction($controller);

Booter::shutdown();
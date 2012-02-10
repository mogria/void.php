<?php

namespace Void;

require_once 'config/consts.php';
require_once 'lib/Autoloader/Autoloader.php';

Booter::boot();

Request::init();

$controller = Dispatcher::getController();

Dispatcher::callMethod($controller);

Booter::shutdown();
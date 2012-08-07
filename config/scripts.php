<?php

namespace Void;

define('REL', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . ".."));
chdir(REL);

require_once 'config/consts.php';
require_once 'autoload.php';
require 'config/environments.php';


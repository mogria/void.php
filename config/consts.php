<?php

namespace Void;
// The URL to the location where this framework is example /myblog/
define('BASEURL', rtrim(dirname($_SERVER['SCRIPT_NAME']), "/") . "/");

// The full URL (example: http://example.org/myblog/)
define('FULLURL','http' . (!empty($_SERVER['HTTPS']) ? "s" : "")  . '://'
    . $_SERVER['HTTP_HOST']
    . ($_SERVER['SERVER_PORT'] != 80 ? ":" . $_SERVER['SERVER_PORT'] : "")
    .  BASEURL);

// the shorter the better ;-)
define('DS', DIRECTORY_SEPARATOR);

// the path in the file system to the framework
define('ROOT', realpath('.') . DS);


// the environments
define('PRODUCTION', 'production');
define('TEST', 'test');
define('DEVELOPMENT', 'development');

<?php

// The URL to the location where this framework is
define('BASEURL', 
    'http' . (!empty($_SERVER['HTTPS']) ? "s" : "")  . '://' 
    . $_SERVER['HTTP_HOST']
    . $_SERVER['SERVER_PORT'] != 80 ? ":" . $_SERVER['SERVER_PORT'] : ""
    . dirname($_SERVER['SCRIPT_NAME'])
);

// the shorter the better ;-)
define('DS', DIRECTORY_SEPARATOR);

// the path in the file system to the framework
define('ROOT', realpath('.') . DS);


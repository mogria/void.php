<?php

define('BASEURL', 
    'http' . (!empty($_SERVER['HTTPS']) ? "s" : "")  . '://' 
    . $_SERVER['HTTP_HOST']
    . $_SERVER['SERVER_PORT'] != 80 ? ":" . $_SERVER['SERVER_PORT'] : ""
    . dirname($_SERVER['SCRIPT_NAME'])
);

define('DS', DIRECTORY_SEPARATOR);

define('ROOT', realpath('.') . DS);


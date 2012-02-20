<?php
/**
 * @author Mogria
 * @package Void.php
 *
 * A Simple MVC Framework written in PHP.
 */

// All the files of the framework use this namespace
namespace Void;

// This defines some useful constants
require_once 'config/consts.php';
// For automated loading of classes
require_once 'autoload.php';

// Startup
Booter::boot();

// Read the request
$request = new Request();

// instanciate the Dispatcher
$dispatcher = new Dispatcher($request);

// Get an instance of the Controller
$controller = $dispatcher->getController();

// Execute the action according to the request
echo $controller->executeAction($dispatcher);

// Clean up
Booter::shutdown();
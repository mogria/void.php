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
// the configuration
require_once 'config/environments.php';

// Startup
Booter::boot();

$redirect_loop_limit = 10;
$redirect_loop_count = 0;

$resolve_request = true;

$redirect = null;
do {
  // Read the request
  $request = new Request($redirect, $resolve_request);
  $resolve_request = false;

  // instanciate the Dispatcher
  $dispatcher = new Dispatcher($request);

  // Get an instance of the Controller
  $controller = $dispatcher->getController();

  // Execute the action according to the request
  $redirect = null;
  $content = $controller->executeAction($dispatcher, $redirect);

  // increase redirect_loop_count to check wheter we are in a redirect loop
  $redirect_loop_count = $request->compareTo($redirect) ? $redirect_loop_count + 1 : 0;

// repeat these steps if the action wants to redirect to an other controller
} while($content === null && $redirect !== null && $redirect_loop_count < $redirect_loop_limit);

// print the content
echo $content;

// Clean up
Booter::shutdown();

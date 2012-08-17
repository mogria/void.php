<?php

namespace Void;

Router::configure(function($route) {
  $route->match('/', '/pages', 'root');
  $route->match('/:[0-9\.]++numbers', '/pages/math/+/:numbers', 'add');
  $route->match('/:[0-9\.]+-numbers', '/pages/math/-/:numbers', 'sub');
  $route->match('/:[0-9\.]+*numbers', '/pages/math/*/:numbers', 'mul');
  $route->match('/:[0-9\.]+:numbers', '/pages/math/:/:numbers', 'div');
  $route->match('/:[0-9]{2,3}nr', '/pages/about/:nr');
  $route->match('/:page', '/pages/:page');
  $route->match('/_:controller', '/:controller');
  $route->match('/:controller/:action', '/:controller/:action');
  $route->match('/:controller/:action/:+params', '/:controller/:action/:params');
});

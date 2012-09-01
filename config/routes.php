<?php

namespace Void;

Router::configure(function($route) {
  $route->match('/', '/posts', 'root');
  $route->match('/about', '/pages/about');
  $route->match('/:controller', '/:controller');
  $route->match('/:controller/:action', '/:controller/:action');
  $route->match('/:controller/:action/:params', '/:controller/:action/:params');
});

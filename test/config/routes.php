<?php

namespace Void;

Router::configure(function($route) {
  $route->match('/', '/pages', 'root');
  $route->match('/:page', '/pages/:page');
  $route->match('/_:controller', '/:controller');
  $route->match('/:controller/:action', '/:controller/:action');
  $route->match('/:controller/:action/:params', '/:controller/:action/:params');
});

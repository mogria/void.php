<?php

namespace Void;

VoidBase::setRoutes(function($route) {
  $route->match('/(.*)', '\1');
});

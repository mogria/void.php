<?php
namespace Void;
require_once dirname(__FILE__) . '/../../config/scripts.php';
Script::init();

echo serialize(Script::parse('f::r:cq', Array('quiet', 'config:', 'void')));

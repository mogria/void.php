<?php

namespace Void;

require_once 'config/consts.php';
require_once 'autoload.php';

class ScriptTest extends \PHPUnit_Framework_TestCase {
  
  public function setUp() {
  }

  public function tearDown() {
  }

  public function testParse() {
    $cmd = '/usr/bin/env php -f test/script/param_test.php -- -f --quiet --config /etc/conf.d/example.conf -r value -c dafuq othervalue';
    $output = "";
    $handle = popen($cmd, 'r');
    while(false != ($out = fread($handle, 1024))) {
      $output .= $out;
    }
    pclose($handle);
    
    echo "OUTPUT: ";
    var_dump($output);

    
    $this->assertEquals(Array(
      'f' => false,
      'r' => 'value',
      'c' => false,
      'q' => null,
      'quiet' => false,
      'config' => '/etc/conf.d/example.conf',
      'void' => null
    ), unserialize($output));
  }

  public function testScanScripts() {
    $this->assertEquals(Array("scripts/generate", "scripts/console", "scripts/clean"), Script::scan_scripts());

  }

  public function testCall() {
  }
}

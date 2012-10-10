<?php

namespace Void;

require __DIR__ . DIRECTORY_SEPARATOR . 'test_bootstrap.php';

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

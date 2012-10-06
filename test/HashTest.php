<?php

namespace Void;

require __DIR__ . DIRECTORY_SEPARATOR . 'test_bootstrap.php';

class HashTest extends \PHPUnit_Framework_TestCase {

  public function setUp() {
    Hash::setConfig(new Config(DEVELOPMENT, function($cfg) {
      $cfg->hash_iterations = 42;
      $cfg->hash_algo = 'whirlpool';
      $cfg->hash_secret = 'DAFUQ!';
    }, true));
  }

  public function testGenerateSalt() {
    $salt = Hash::generateSalt();
    $this->assertEquals(32, strlen($salt));
    $this->assertEquals(1, preg_match("/^[0-9a-f]*/D", $salt));

    $this->assertNotEquals(Hash::generateSalt(), $salt);
  }

  public function testCreate() {
    $hash = Hash::create("FUS RO DAH!");
    $this->assertEquals(128 + 1 + 32, strlen($hash));
    $this->assertEquals(1, preg_match("/^[0-9a-f]*:[0-9a-f]*$/D", $hash));

    $hash = Hash::create("FUS RO DAH!", '8603a0f84c5ee600a42725c12cf22e84');
    $this->assertEquals(128 + 1 + 32, strlen($hash));
    $this->assertEquals(1, preg_match("/^[0-9a-f]*:8603a0f84c5ee600a42725c12cf22e84$/D", $hash));
  }

  public function testCompare() {
    $hash = Hash::create("FUS RO DAH!");
    $hash2 = Hash::create("FUS RO DAH!!!!!!!!!");

    $this->assertTrue(Hash::compare("FUS RO DAH!", $hash));
    $this->assertTrue(Hash::compare("FUS RO DAH!!!!!!!!!", $hash2));
    $this->assertFalse(Hash::compare("FUS RO DAH!", $hash2));
    $this->assertFalse(Hash::compare("WHEEEEEEE", $hash));
  }

}

<?php

namespace Void;

require __DIR__ . DIRECTORY_SEPARATOR . 'test_bootstrap.php';

class CacheTest extends \PHPUnit_Framework_TestCase {
  
  protected $cache;

  public function setUp() {
  }

  public function tearDown() {
    Cache::clear();
  }

  public function testGenPathByIdentifier() {
    $this->assertEquals("tmp" . DS . "cache" . DS . md5("void") . ".tmp", Cache::genPathByIdentifier("void"));
  }

  public function testCache() {
    $this->assertEquals(false, Cache::cache("test"));
    $this->assertEquals(true, Cache::cache("test", "I think I should cache this text."));
    $this->assertEquals(true, Cache::cache("test2", "I think I should cache another text."));
    $this->assertEquals("I think I should cache this text.", Cache::cache("test"));
    $this->assertEquals("I think I should cache another text.", Cache::cache("test2"));
  }

  public function testCacheModified() {
    $time = time();
    $this->assertEquals(true, Cache::cache("test", "I think I should cache this text."));
    $this->assertEquals($time, Cache::cache_modified("test"));
  }
}

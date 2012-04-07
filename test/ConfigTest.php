<?php

namespace Void;

require_once 'config/consts.php';
require_once 'autoload.php';

$config = new Config(TEST, function($cfg) {
  $cfg->test = 15;
  $cfg->testconfigurable_test = "asdasd";
});


class ConfigTest extends \PHPUnit_Framework_TestCase {
  protected $config;

  public function setUp() {
    global $config;
    // create a simple config in the test environment
    $this->config = clone $config; 
    $this->configurable = new TestConfigurable();
    VoidBase::setConfig($this->config);
  }

  public function testIsEnvironment() {
    // go through all the environments
    $this->assertTrue($this->config->isEnvironment(DEVELOPMENT));
    $this->assertTrue($this->config->isEnvironment(TEST));
    $this->assertTrue($this->config->isEnvironment(PRODUCTION));
    
    // test something else
    $this->assertFalse($this->config->isEnvironment("random shit"));
  }

  public function testGetEnvironment() {
    $this->assertTrue(TEST == $this->config->getEnvironment());
  }

  public function testSetEnvironment() {
    // go through all the environments
    $this->config->setEnvironment(DEVELOPMENT);
    $this->assertTrue(DEVELOPMENT == $this->config->onDevelopment());
    $this->config->setEnvironment(TEST);
    $this->assertTrue(TEST == $this->config->onTest());
    $this->config->setEnvironment(PRODUCTION);
    $this->assertTrue(PRODUCTION == $this->config->onProduction());

    // other vales should not be accepted by the Config object
    $this->config->setEnvironment("some random value 15");
    $this->assertFalse("some random value 15" == $this->config->getEnvironment());
  }

  public function testConvertClassName() {
    $this->assertEquals("void_ness_shitclass", Config::convertClassName("Void\\Ness\\ShitClass"));
  }

  public function testConcatKeys() {
    $this->assertEquals("weisch_we_rockts", Config::concatKeys("weisch", "we", "rockts"));
    $this->assertEquals("weisch_we_rockts", Config::concatKeys("", "weisch", "we", "", "rockts"));
    $this->assertEquals("weisch_we_rockts", Config::concatKeys(array("weisch", "we", "rockts")));
    $this->assertEquals("", Config::concatKeys());
    $this->assertEquals("", Config::concatKeys(""));
    $this->assertEquals("", Config::concatKeys("", ""));
    $this->assertEquals("", Config::concatKeys(array()));
    $this->assertEquals("", Config::concatKeys(array("")));
    $this->assertEquals("", Config::concatKeys(array("", "")));
  }

  public function testCalledClass() {
    /* $this->config->config(function($cfg) {
      $cfg->config(function($cfg) {

      }, 'all');
    }); */
    $this->configurable->testCalledClass($this);
  }

  public function testGet() {
    $this->configurable->testGet($this);
  }

  public function testSet() {
    $this->configurable->testSet($this);
  }

  public function testConfig() {
    $this->configurable->testConfig($this);
  }
}


class TestConfigurable extends VoidBase {
  public function testCalledClass($test) {
    $test->assertEquals("TestConfigurable", Config::getCalledClass());
  }

  public function testGet($test) {
    $test->assertEquals("asdasd", self::$config->test);
  }

  public function testSet($test) {
    self::$config->void = "trololol";
    self::$config->test = "dsadsa";

    $test->assertEquals("trololol", self::$config->void);
    $test->assertEquals("dsadsa", self::$config->test);
  }

  public function testConfig($test) {
    self::$config->config(function($cfg) {
      $cfg->config(function($cfg) {
        $cfg->all = "test_value";
      }, 'all');

      $cfg->config(function($cfg) {
        $cfg->all = "override_value";
        $cfg->test = "explain";
      }, DEVELOPMENT);

      $cfg->config(function($cfg) {
      }, TEST);

      $cfg->config(Array(
        'key_in_production' => 123,
        'test' => "hui"
      ), PRODUCTION);
    });

    $test->assertEquals(null, self::$config->inexistent_key);
    $test->assertEquals("test_value", self::$config->all);

    // testing DEVELOMENT environment
    self::$config->setEnvironment(DEVELOPMENT);
    $test->assertEquals("override_value", self::$config->all);
    $test->assertEquals("explain", self::$config->test);
    $test->assertEquals(null, self::$config->key_in_production);

    // testing TEST environment
    self::$config->setEnvironment(TEST);
    $test->assertEquals("asdasd", self::$config->test);
    $test->assertEquals("test_value", self::$config->all);
    $test->assertEquals(null, self::$config->key_in_production);


    self::$config->setEnvironment(PRODUCTION);
    $test->assertEquals("hui", self::$config->test);
    $test->assertEquals("test_value", self::$config->all);
    $test->assertEquals(123, self::$config->key_in_production);
  }
}

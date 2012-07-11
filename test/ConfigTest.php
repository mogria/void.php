<?php

namespace Void;

require_once 'config/consts.php';
require_once 'autoload.php';

class ConfigTest extends \PHPUnit_Framework_TestCase {
  protected $config;

  public function setUp() {
    global $config;
    // clone the clobal config and use it for the tests
    $this->config = $config = new Config(TEST, function($cfg) {
      $cfg->test = 15;
      $cfg->testconfigurable_test = "asdasd";
    }, false);
    // create a configurable object
    $this->configurable = new TestConfigurable();
    // all the configurable objects should now use the cloned config
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
    // are we in the TEST environment?
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
    // check uf the concating of the keys works properly
    $this->assertEquals("weisch_we_rockts", Config::concatKeys("weisch", "we", "rockts"));
    $this->assertEquals("weisch_we_rockts", Config::concatKeys("", "weisch", "we", "", "rockts"));
    $this->assertEquals("weisch_we_rockts", Config::concatKeys(array("weisch", "we", "rockts")));
    // does it also work with empty values?
    $this->assertEquals("", Config::concatKeys());
    $this->assertEquals("", Config::concatKeys(""));
    $this->assertEquals("", Config::concatKeys("", ""));
    $this->assertEquals("", Config::concatKeys(array()));
    $this->assertEquals("", Config::concatKeys(array("")));
    $this->assertEquals("", Config::concatKeys(array("", "")));
  }

  /* the following tests simply call the method of the configurable class to do the tests*/
  public function testCalledClass() {
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


/* A helper Class */
class TestConfigurable extends VoidBase {
  public function testCalledClass($test) {
    // does the Config object trace the correct class?
    $test->assertEquals("TestConfigurable", Config::getCalledClass());
  }

  public function testGet($test) {
    // can we read the proper configuration for this class?
    $test->assertEquals("asdasd", self::$config->test);
  }

  public function testSet($test) {
    // set some values
    self::$config->void = "trololol";
    self::$config->test = "dsadsa";

    // get them
    $test->assertEquals("trololol", self::$config->void);
    $test->assertEquals("dsadsa", self::$config->test);
  }

  public function testConfig($test) {
    // set multiple values in ALL THE environments
    self::$config->config(function($cfg) {
      // for all the environments
      $cfg->config(function($cfg) {
        $cfg->all = "test_value";
      }, 'all');

      // only for DEVELOPMENT
      $cfg->config(function($cfg) {
        // this overrides the value defined in 'all'
        $cfg->all = "override_value";
        // this overrides the value defined when we created the Config object
        $cfg->test = "explain";
      }, DEVELOPMENT);

      $cfg->config(function($cfg) {
      }, TEST);

      // this should also work using arrays
      $cfg->config(Array(
        'key_in_production' => 123,
        'test' => "hui"
      ), PRODUCTION);
    });

    // is null returned if the key doesn't exists?
    $test->assertEquals(null, self::$config->inexistent_key);
    $test->assertEquals("test_value", self::$config->all);

    // testing DEVELOMENT environment values
    self::$config->setEnvironment(DEVELOPMENT);
    $test->assertEquals("override_value", self::$config->all); // proper override?
    $test->assertEquals("explain", self::$config->test);
    $test->assertEquals(null, self::$config->key_in_production);

    // testing TEST environment values
    self::$config->setEnvironment(TEST);
    $test->assertEquals("asdasd", self::$config->test);
    $test->assertEquals("test_value", self::$config->all);
    $test->assertEquals(null, self::$config->key_in_production);


    // testing PRODUCTION environment values
    self::$config->setEnvironment(PRODUCTION);
    $test->assertEquals("hui", self::$config->test);
    $test->assertEquals("test_value", self::$config->all);
    $test->assertEquals(123, self::$config->key_in_production);
  }
}

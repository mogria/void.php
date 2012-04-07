<?php

namespace Void;

require_once 'config/consts.php';
require_once 'lib/Base/VoidBase.php';
require_once 'lib/View/VirtualAttribute.php';
require_once 'lib/Config/Config.php';

class ConfigTest extends \PHPUnit_Framework_TestCase {
  protected $config;

  public function setUp() {
    // create a simple config in the test environment
    $this->config = new Config(TEST);
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

 /*  public function testConvertClassName() {
 }*/
}

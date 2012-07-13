<?php

namespace Void;

$overwrite_environment = TEST;
require_once 'config/consts.php';
require_once 'autoload.php';
require 'config/environments.php';
require_once 'test/Models/User.php';

Booter::boot(true);

class SelectTagTest extends \PHPUnit_Framework_TestCase {
   
  protected $select;
  public function setUp() {
    $this->select = new HTML\SelectTag("test",
      Array('Pizza', 'Banana', 'Cookie', 'Fries'));
    $this->expected_content = <<<OUTPUT
<option value="Pizza">Pizza</option>
<option value="Banana">Banana</option>
<option value="Cookie">Cookie</option>
<option value="Fries">Fries</option>
OUTPUT;
    // test with an associative array
    $this->select_assoc = new HTML\SelectTag("assoc_test",
      Array('1' => 'Pizza', '5' => 'Banana', '3' => 'Fries', '2' => 'Cookie'));
    $this->expected_content_assoc = <<<OUTPUT
<option value="1">Pizza</option>
<option value="5">Banana</option>
<option value="3">Fries</option>
<option value="2">Cookie</option>
OUTPUT;
    // test with an Model
    $this->select_model = new HTML\SelectTag("model_test",
      Array(
        new User(Array('name' => 'Trollinger', 'id' => 5)),
        new User(Array('name' => 'Mogria', 'id' => 11)),
        new User(Array('name' => 'Container15', 'id' => 15))
      ), Array(
        '%s - %s',
        'id',
        'name'
      )
    );
    $this->expected_content_model = <<<OUTPUT
<option value="5">5 - Trollinger</option>
<option value="11">11 - Mogria</option>
<option value="15">15 - Container15</option>
OUTPUT;
  }

  public function testSetType() {
    $type_before = $this->select->getType();
    // the set function should always return false
    $this->assertFalse($this->select->setType("any value"));
    // the value shouldn't change<
    $this->assertEquals($type_before, $this->select->getType());
  }

  public function testGetType() {
    $this->assertEquals("select", $this->select->getType());
  }

  public function testGetContent() {
    $this->assertEquals($this->expected_content, $this->select->getContent());
    $this->assertEquals($this->expected_content_assoc, $this->select_assoc->getContent());
    $this->assertEquals($this->expected_content_model, $this->select_model->getContent());
  }

  public function testOutput() {
    $this->assertEquals(<<<SELECT
<select name="test">{$this->expected_content}</select>
SELECT
      , $this->select->output());
    $this->assertEquals(<<<SELECT
<select name="assoc_test">{$this->expected_content_assoc}</select>
SELECT
      , $this->select_assoc->output());
    $this->assertEquals(<<<SELECT
<select name="model_test">{$this->expected_content_model}</select>
SELECT
      , $this->select_model->output());
  }
}

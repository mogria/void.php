<?php

namespace Void;

require_once 'config/consts.php';
require_once 'autoload.php';

class OptionTagTest extends \PHPUnit_Framework_TestCase {
  
  protected $option;
  protected $option_label;
  protected $option_label_class;

  public function setUp() {
    $this->option             = new HTML\OptionTag("value");
    $this->option_label       = new HTML\OptionTag("value2", "label");
    $this->option_label_class = new HTML\OptionTag("value3", "label2", Array('class' => 'test_class'));
  }

  public function testSetSelected() {
    $this->option->setSelected(true);
    $this->assertTrue($this->option->isSelected());
    $this->option->setSelected(false);
    $this->assertFalse($this->option->isSelected());
  }

  public function testIsSelected() {
    $this->assertFalse($this->option->isSelected());
  }

  public function getLabel() {
    $this->assertEquals("value", $this->option->getLabel());
    $this->assertEquals("label", $this->option_label->getLabel());
    $this->assertEquals("label2", $this->option_label_class->getLabel());
  }

  public function setLabel() {
    $this->option->setLabel("an label");
    $this->assertEquals("an label", $this->option->getLabel());
    $this->option->setLabel("an other label");
    $this->assertEquals("an other label", $this->option->getLabel());
  }

  public function testGetValue() {
    $this->assertEquals("value", $this->option->getValue());
    $this->assertEquals("value2", $this->option_label->getValue());
    $this->assertEquals("value3", $this->option_label_class->getValue());
  }

  public function testSetValue() {
    $this->option->setValue("an label");
    $this->assertEquals("an label", $this->option->getValue());
    $this->option->setValue("an other label");
  }

  public function testOutput() {
    $this->assertEquals('<option value="value">value</option>', $this->option->output());
    $this->assertEquals('<option value="value2">label</option>', $this->option_label->output());
    $this->assertEquals('<option class="test_class" value="value3">label2</option>', $this->option_label_class->output());
  }
}

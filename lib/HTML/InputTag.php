<?php

namespace Void;

class InputTag extends Tag {
  protected $tag_name = "input";
  public function __construct($name, $type, $content, $attributes = Array()) {
    parent::__construct($this->tag_name, null, $attributes);
    $this->setName($name);
    $this->setType($type);
    $this->setContent($content);
  }

  public function setName($value) {
    $this->name = $value;
  }

  public function getName() {
    return $this->name;
  }

  public function setContent($value) {
    $this->setValue($value);
  }

  public function getContent() {
    return $this->getValue();


  public function setValue($value) {
    this->value = $value;
  }

  public function getValue() {
    return $this->value;
  }
}

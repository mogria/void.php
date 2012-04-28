<?php

namespace Void;

class TextareaTag extends InputTag {
  protected static $tag_name = "textarea";
  public function __construct($name, $content, Array $attributes = Array()) {
    parent::__construct($name, "text", $content, $attributes);
  }

  public function getValue() {
    return $this->content;
  }

  public function setValue($value) {
    $this->content = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
  }

  /** we dont need a 'type' - attribute **/
  public function setType($type) { }
  public function getType($type) {
    return null;
  }
}

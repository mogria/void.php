<?php

namespace Void;

class RadioTag extends InputTag {
  protected static $tag_name = "";

  public function __construct($name, $content, Array $attributes = Array()) {
    parent::__construct($name, "", $content, $attributes);
  }

  public function setType($new_type) {
    return false;
  }

  public function getType() {
    return "radio";
  }

  public function setContent($content) {
    $this->content = Array();
    $keys = array_values($content) == $content;
    foreach($content as $key => $value) {
      if($keys) {
        $this->content[$value] = new InputRadioTag($value);
      } else {
        $this->content[$key] = new InputRadioTag($value);
      }
    }

  }

  public function getContent() {
    return implode("\n", array_map(function($element) {
      return $element->output();
    }, $this->content));
  }
}

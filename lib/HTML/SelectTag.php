<?php

namespace Void;


class SelectTag extends InputTag {
  protected static $tag_name = "select";

  public function __construct($name, $content, Array $attributes = Array()) {
    parent::__construct($name, "select", $content, $attributes);
    $this->delete('type');
  }

  public function setType($new_type) {
    return false;
  }

  public function getType() {
    return "select";
  }

  public function setContent() {
    $this->content = Array();
    $keys = array_values($content) == $content;
    foreach($content as $key => $value) {
      if($keys) {
        $this->content[] = new OptionTag($value);
      } else {
        $this->content[] = new OptionTag($key, $value);
      }
    }
  }

  public function getContent() {
    return implode("\n", array_map(function($element) {
      return $element->output();
    }, $this->content));
  }
}

<?php

namespace Void\HTML;
use \ActiveRecord\Model;


class SelectTag extends InputTag {
  protected static $tag_name = "select";
  protected $label_system;

  public function __construct($name, $content, $label = Array(), Array $attributes = Array()) {
    $this->label_system = $label;
    parent::__construct($name, "select", $content, $attributes);
    $this->exists('type') && $this->delete('type');
  }

  public function setType($new_type) {
    return false;
  }

  public function getType() {
    return "select";
  }

  public function setContent($content) {
    $this->content = Array();
    $keys = array_values($content) === $content;
    foreach($content as $key => $value) {
      if($value instanceof Model) {
        $label = array_values($this->label_system);
        $format = array_shift($label);
        foreach($label as &$label_value) {
          $label_value = $value->$label_value;
        }
        $this->content[] = new OptionTag($value->{$value->get_primary_key(true)}, call_user_func_array('sprintf', array_merge(array($format), $label)));
      } else {
        if($keys) {
          $this->content[] = new OptionTag($value);
        } else {
          $this->content[] = new OptionTag($key, $value);
        }
      }
    }
  }

  public function getContent() {
    return implode("\n", array_map(function($element) {
      return $element->output();
    }, $this->content));
  }
}

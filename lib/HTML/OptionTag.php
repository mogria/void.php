<?php

namespace Void\HTML;

class OptionTag extends Tag {

  public function __construct($value, $label = null, Array $attributes = Array()) {
    $label === null && $label = $value;
    $attributes['value'] = $value;
    parent::__construct("option", $label, $attributes);
  }

  public function setSelected($bool) {
    if($bool) {
      $this->selected = null;
    } else {
      $this->delete('selected');
    }
  }

  public function isSelected() {
    return (bool)$this->exists('selected');
  }

  public function setLabel($new_label) {
    $this->setContent($new_label);
  }

  public function getLabel() {
    return $this->getContent();
  }

  public function setValue($new_value) {
    $this->value = $new_value;
  }

  public function getValue() {
    return $this->value;
  }
}

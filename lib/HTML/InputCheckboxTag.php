<?php

namespace Void\HTML;

class InputCheckboxTag extends InputTag {
  public function __construct($name, $value, Array $attributes = Array()) {
    parent::__construct($name, "checkbox", $value, $attributes);
  }
}

<?php

namespace Void\HTML;

class InputTextTag extends InputTag {
  public function __construct($name, $value, Array $attributes = Array()) {
    parent::__construct($name, "radio", $value, $attributes);
  }
}

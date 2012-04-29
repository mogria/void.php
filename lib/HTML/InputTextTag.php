<?php

namespace Void;

class InputTextTag extends InputTag {
  public function __construct($name, $content = "", Array $attributes = Array()) {
    parent::__construct($name, "text", $content, $attributes);
  }
}

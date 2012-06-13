<?php

namespace Void\HTML;

class InputPasswordTag extends InputTag {
  public function __construct($name, $content, Array $attributes = Array()) {
    parent::__construct($name, "password", $content, $attributes);
  }

  /** don't let passwords be auto filled into the form **/
  public function setContent($value) {
    $this->content = null;
  }

  public function getContent() {
    return null;
  }
}

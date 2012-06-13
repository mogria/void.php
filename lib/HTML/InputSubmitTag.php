<?php

namespace Void\HTML;

class InputSubmitTag extends InputTag {
  public function __construct($content = "", Array $attributes = Array()) {
    parent::__construct("form_sent", "submit", $content, $attributes);
  }
}

<?php

namespace Void;

class ATag extends Tag {
  public function __construct($content = null, $target = '#', Array $attributes = Array()) {
    is_array($target) && $target = Router::link($target);
    parent::__construct("a", $content, $attributes);
    $this->setTarget($target);
  }

  public function setTarget($link) {
    $this->href = $link;
  }

  public function getTarget() {
    return $this->href;
  }
}
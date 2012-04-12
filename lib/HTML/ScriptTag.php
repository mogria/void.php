<?php

namespace Void;

class ScriptTag extends Tag {
  public function __construct($src = null, Array $attributes = Array()) {
    $this->type = "text/javascript";
    parent::__construct("script", " ", $attributes);
    $this->setTarget(Router::linkJS($src));
  }

  public function setTarget($link) {
    $this->src = (strpos($link, BASEURL) !== 0) && (strpos($link, FULLURL) !== 0) ? BASEURL . $link : $link;
  }

  public function getTarget() {
    return $this->src;
  }
}

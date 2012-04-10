<?php

namespace Void;

class LinkTag extends Tag {
  public function __construct($href = null, Array $attributes = Array()) {
    $this->type = "text/css";
    $this->rel = "stylesheet";
    parent::__construct("link", null, $attributes);
    $this->setTarget(Router::linkCSS($href));
  }

  public function setTarget($link) {
    $this->href =  (strpos($link, BASEURL) !== 0) ? BASEURL . $link : $link;
  }

  public function getTarget() {
    return $this->href;
  }
}

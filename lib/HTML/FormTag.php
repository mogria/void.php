<?php


class FormTag extends Tag {
  protected $contents = Array();
  public function __construct($method, $action, $content = null, Array $attributes = Array()) {
    $this->method = $method;
    parent::__construct("form", $content, $attributes);
  }

  public function setTarget($action) {
    $this->action = $action;
  }

  public function getTarget() {
    return $this->action;
  }
}


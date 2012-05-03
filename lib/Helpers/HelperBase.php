<?php

namespace Void;

class HelperBase extends VoidBase {
  protected $template;
  public function __construct(Template $template) {
    $this->template = $template;
  }

  public function __call($method, $args) {
    $this->template->__call($method, $args);
  }
}

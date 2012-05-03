<?php

namespace Void;

abstract class ApplicationController extends ControllerBase {
  public function initialize() {
    $this->title = "";
  }
}

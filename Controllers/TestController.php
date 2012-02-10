<?php

namespace Void;

class TestController extends ControllerBase {
  public function action_index() {
    return "Test";
  }
  
  public function action_test() {
    return "vness?";
  }
}
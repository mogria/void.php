<?php

namespace Void;

class PagesController extends ControllerBase {
  public function action_index() { }
  public function action_about() {
    $this->title = "About";
  }
  public function action_get_involved() {
    $this->title = "Get Involved";
  }
  public function action_help() {
    $this->title = "Help";
  }

  public function action_test_forms() {
    $this->title = "Just testing the Form helper";
  }
}

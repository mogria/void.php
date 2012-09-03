<?php

namespace Void;

class PagesController extends ApplicationController {

  public function action_index() {
    Router::redirect_about();
  }
  
  public function action_about() {
  }
}

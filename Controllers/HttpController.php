<?php

namespace Void;

class HttpController extends ApplicationController {
  public function action_index() {
    return 'http/404';
  }

  public function action_403() {
  }

  public function action_404() {
  }
}

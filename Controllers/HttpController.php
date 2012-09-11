<?php

namespace Void;

class HttpController extends ApplicationController {
  public function action_index() {
    return 'http/404';
  }

  /**
   * everybody can see these error messages
   */
  public function authenticate($action) {
    return true;
  }

  public function action_403() {
  }

  public function action_404() {
  }
}

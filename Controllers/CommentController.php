<?php

namespace Void;

class CommentController extends ApplicationController {

  public function action_new() {
    $this->comment = new Comment($_POST);
  }
}

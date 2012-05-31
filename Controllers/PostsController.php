<?php

namespace Void;

class PostsController extends ApplicationController {

  public function action_index() {
    $this->posts = Post::all();
  }
  public function action_show() {
  }
  public function action_new() {
  }
  public function action_create() {
  }
  public function action_edit() {
  }
  public function action_delete() {
  }

}

<?php

namespace Void;
use Exception;

class PostsController extends ApplicationController {

  public function action_index() {
    $this->posts = Post::all();
  }

  public function action_show($id = null) {
    try {
      if($id === null) {
        throw new \Exception();
      }
      $this->post = Post::find((int)$id);
    } catch(Exception $ex) {
      header("404 Not Found");
      $this->post = null;
    }
  }
  public function action_new() {
    $this->post = new Post($_POST);
    // @todo: change this when we have an authentification system
    $this->post->user_id = 1;
    if($this->post->save()) {
      Router::redirect('posts', 'show', Array($this->post->id));
    }
  }
  public function action_create() {
  }
  public function action_edit() {
  }
  public function action_delete() {
  }

}

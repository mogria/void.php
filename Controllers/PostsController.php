<?php

namespace Void;
use Exception;

class PostsController extends ApplicationController {

  public function action_index() {
    $this->posts = Post::all();
  }

  public function action_show($id = null) {
    try {
      $this->post = Post::find((int)$id);
    } catch(Exception $ex) {
      header("404 Not Found");
      $this->post = null;
    }
  }
  public function action_new() {
    $this->categories = Category::all();
    $this->post = new Post($_POST);
    // @todo: change this when we have an authentification system
    $this->post->user_id = 1;
    if(isset($_POST['form_sent']) && $this->post->save()) {
      Router::redirect_post($this->post->id);
    }
  }
  public function action_edit($id = null) {
    try {
      $this->categories = Category::all();
      $this->post = Post::find((int)$id);
      if(isset($_POST['form_sent']) && $this->post->update_attributes($_POST)) {
        throw new Exception();
      }
    } catch(Exception $ex) {
      Router::redirect_post($id);
    }
  }
  public function action_delete($id = null) {
    try {
      var_dump($id);
      Post::find($id)->delete();
      Flash::success('The post was sucessfully deleted');
      Router::redirect_posts();
    } catch(Exception $ex) {
      Flash::error('Unable to delete post.');
      Router::redirect_posts();
    }
  }

}

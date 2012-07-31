<?php

namespace Void;

class UserController extends ApplicationController {

  public function action_index() {
  }

  public function action_login() {
    $this->user = new User(Array('name' => ''));
    if(isset($_POST['name'], $_POST['text_password'])) {
      $user = User::find_by_name($_POST['name']);
      if($user !== null && (($user->text_password = $_POST['text_password']) || 1) && $user->login()) {
        Flash::success('Successfully logged in');
        Router::redirect(null);
      } else {
        $this->user = new User(Array('name' => $_POST['name']));
        Flash::error('Username and/or Password wrong');
      }
    }
  }
  public function action_logout() {
  }
  public function action_show() {
  }
  public function action_new() {
  }
  public function action_edit() {
  }
  public function action_delete() {
  }

}

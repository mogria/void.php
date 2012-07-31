<?php

namespace Void;

class UserController extends ApplicationController {

  public function action_index() {
  }

  public function action_login() {
    // @todo: what if already logged in?
    $this->user = new User(Array('name' => ''));
    if(isset($_POST['name'], $_POST['text_password'])) {
      $user = User::find_by_name($_POST['name']);
      if($user !== null && (($user->text_password = $_POST['text_password']) || 1) && $user->login()) {
        $user->last_login = new \DateTime();
        $user->save();
        Flash::success('Successfully logged in');
        Router::redirect(null);
      } else {
        $this->user = new User(Array('name' => $_POST['name']));
        Flash::error('Username and/or Password wrong');
      }
    }
  }

  public function action_logout() {
    // @todo: what if not logged in?
    Session::user()->logout();
    Flash::success('Successfully logged out');
    Router::redirect(null);
  }

  public function action_show($id = null) {
    // @todo: what if not logged in?
    $this->user = ($id === null) ? Session::user() : User::find_by_id($id);
    if($this->user === null) {
      Flash::error('This user doesn\'t exist');
      Router::redirect(null);
    }
  }

  public function action_new() {
  }
  public function action_edit() {
  }
  public function action_delete() {
  }

}

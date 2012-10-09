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
        $user->last_login = new \DateTime();
        $user->save();
        Flash::success('Successfully logged in');
        Router::redirect_root();
      } else {
        $this->user = new User(Array('name' => $_POST['name']));
        Flash::error('Username and/or Password wrong');
      }
    }
  }

  public function action_logout() {
    Session::user()->logout();
    Flash::success('Successfully logged out');
    Router::redirect_root();
  }

  public function action_show($id = null) {
    // @todo: what if not logged in?
    $this->user = ($id === null)
      ? (Session::user()->role->login ? Session::user() : null)
      : User::find_by_id($id);
    if($this->user === null ) {
      Flash::error('This user doesn\'t exist');
      Router::redirect_root();
    }
  }

  public function action_new() {
    if(Session::user()->get_role()->allowedTo("login")) {
      Flash::error('You\'re logged in. You don\'t need to register again.');
      Router::redirect_root();
    }

    $this->user = new User($_POST);

    if(isset($_POST['form_sent'])) {
      $this->user->admin = 0;
      if($this->user->save()) {
        Flash::success('Registration successful');
        Router::redirect_login();
      }
    }
  }
  public function action_edit() {
  }
  public function action_delete() {
  }

}

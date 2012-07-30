<?php

namespace Void;

class User extends ModelAuthentification {

  public $text_password = null;
  static $before_save = Array('hash_password');

  public function login() {
    // are the hashes the same
    $password_correct = Hash::compare($this->text_password, $this->password);

    // set the session, if the hashes are the same
    $password_correct && Session::user($this);

    return $password_correct;
  }

  public function logout() {
    Session::user(null);
  }

  public function get_role() {
    return Session::user_exists() ? RoleManager::get($this->read_attribute('role')) : new UnregistredRole();
  }

  public function hash_password() {
    if(is_string($this->text_password)) {
      $this->password = Hash::create($this->text_password);
      $this->text_password = null;
    }
  }
}

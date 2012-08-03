<?php

namespace Void;

class User extends ModelAuthentification {

  public $text_password = null;
  static $before_save = Array('hash_password');

  public static function auth_init($force) {
    if($force || !(Session::user() instanceof User))  {
      Session::user(new User(Array('name' => 'Anonymous')));
    }
  }

  public function login() {
    // are the hashes the same
    $password_correct = Hash::compare($this->text_password, $this->password);

    // set the session, if the hashes are the same
    $password_correct && Session::user($this);

    return $password_correct;
  }

  public function logout() {
    self::auth_init(true);
  }

  public function get_role() {
    return Session::user()->id === null
      ? new UnknownRole()
      : RoleManager::get($this->read_attribute('role'));
  }

  public function hash_password() {
    if(is_string($this->text_password)) {
      $this->password = Hash::create($this->text_password);
      $this->text_password = null;
    }
  }
}

<?php

/*

+----------------+----------+--------+------+---------+-------------+----------------+
| Name           | Type     | Length | NULL | default | Primary Key | auto increment | 
+----------------+----------+--------+------+---------+-------------+----------------+
| id             | int      | 11     | no   |         | 1           | 1              | 
+----------------+----------+--------+------+---------+-------------+----------------+
| name           | varchar  | 30     | no   |         |             |                | 
+----------------+----------+--------+------+---------+-------------+----------------+
| password       | varchar  | 161    | no   |         |             |                | 
+----------------+----------+--------+------+---------+-------------+----------------+
| password_reset | varchar  | 12     | yes  |         |             |                | 
+----------------+----------+--------+------+---------+-------------+----------------+
| email          | varchar  | 60     | no   |         |             |                | 
+----------------+----------+--------+------+---------+-------------+----------------+
| status         | varchar  | 255    | yes  |         |             |                | 
+----------------+----------+--------+------+---------+-------------+----------------+
| admin          | tinyint  | 1      | no   |         |             |                | 
+----------------+----------+--------+------+---------+-------------+----------------+
| description    | text     |        | yes  |         |             |                | 
+----------------+----------+--------+------+---------+-------------+----------------+
| last_login     | datetime | 19     | yes  |         |             |                | 
+----------------+----------+--------+------+---------+-------------+----------------+
| created_at     | datetime | 19     | yes  |         |             |                | 
+----------------+----------+--------+------+---------+-------------+----------------+
| updated_at     | datetime | 19     | yes  |         |             |                | 
+----------------+----------+--------+------+---------+-------------+----------------+
| fullname       | varchar  | 50     | yes  |         |             |                | 
+----------------+----------+--------+------+---------+-------------+----------------+

*/

namespace Void;

class User extends ModelAuthentification {
  static $has_many = Array('posts', 'comments');
  static $attr_accessible = Array('name', 'email', 'text_password', 'text_password_confirm', 'status', 'description', 'fullname');
  static $validates_presence_of = Array(
    Array('name'),
    //Array('password'),
    Array('email'),
    Array('admin')
  );
  static $validates_length_of = Array(
    Array('name', 'maximum' => 30),
    Array('password', 'maximum' => 161),
    Array('password_reset', 'maximum' => 12),
    Array('email', 'maximum' => 60),
    Array('status', 'maximum' => 255),
    Array('fullname', 'maximum' => 50)
  );

  static $before_save = Array('hash_password');

  protected $text_password = null;
  protected $text_password_confirm = null;

  public function set_text_password($new_text_password) {
    $this->text_password = $new_text_password;

    // setting attribute dirty so the model gets saved when calling save();
    $this->flag_dirty("password");
  }

  public function set_text_password_confirm($new_text_password_confirmation) {
    $this->text_password_confirm = $new_text_password_confirmation;

    // setting attribute dirty so the model gets saved when calling save();
    $this->flag_dirty("password");
  }

  public function get_text_password() {
    return "";
  }


  public function get_text_password_confirm() {
    return "";
  }

  public function get_fullname() {
    return null === ($fullname = $this->read_attribute('fullname')) ? $this->name : $fullname;
  }

  public static function auth_init($force) {
    if($force || !(Session::user() instanceof User)) {
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
    if(Session::user_exists() && $this->id) {
      if($this->admin) {
        return new AdminRole();
      }
      return new UserRole();
    }
    return new UnregistredRole();
  }


  public function hash_password() {
    if(is_string($this->text_password)) {
      $this->password = Hash::create($this->text_password);
      $this->text_password = null;
    }
  }

  public function validate() {
    if($this->text_password_confirm !== null && $this->text_password !== null) {
      if($this->text_password_confirm !== $this->text_password) {
        $this->errors->add('text_password_confirm', 'password do not match');
      }
    }
  }
}
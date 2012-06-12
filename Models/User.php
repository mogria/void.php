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

class User extends \ActiveRecord\Model {
  static $has_many = Array('posts');
  static $validates_presence_of = Array(
    Array('name'),
    Array('password'),
    Array('email'),
    Array('admin')
  );
  static $validates_length_of = Array(
    Array('name', 'maximum' => 30),
    Array('password', 'maximum' => 161),
    Array('password_reset', 'maximum' => 12),
    Array('email', 'maximum' => 60),
    Array('status', 'maximum' => 255),
    Array('admin', 'maximum' => 1),
    Array('last_login', 'maximum' => 19),
    Array('created_at', 'maximum' => 19),
    Array('updated_at', 'maximum' => 19),
    Array('fullname', 'maximum' => 50)
  );

  public function get_fullname() {
    return null === ($fullname = $this->read_attribute('fullname')) ? $this->name : $fullname;
  }
}
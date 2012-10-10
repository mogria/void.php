<?php

/*

+------------+----------+--------+------+---------+-------------+----------------+
| Name       | Type     | Length | NULL | default | Primary Key | auto increment | 
+------------+----------+--------+------+---------+-------------+----------------+
| id         | int      | 11     | no   |         | 1           | 1              | 
+------------+----------+--------+------+---------+-------------+----------------+
| fullname   | varchar  | 50     | yes  |         |             |                | 
+------------+----------+--------+------+---------+-------------+----------------+
| email      | varchar  | 60     | yes  |         |             |                | 
+------------+----------+--------+------+---------+-------------+----------------+
| content    | text     |        | yes  |         |             |                | 
+------------+----------+--------+------+---------+-------------+----------------+
| post_id    | int      | 11     | no   |         |             |                | 
+------------+----------+--------+------+---------+-------------+----------------+
| created_at | datetime | 19     | yes  |         |             |                | 
+------------+----------+--------+------+---------+-------------+----------------+
| updated_at | datetime | 19     | yes  |         |             |                | 
+------------+----------+--------+------+---------+-------------+----------------+
| user_id    | int      | 11     | yes  |         |             |                | 
+------------+----------+--------+------+---------+-------------+----------------+

*/

namespace Void;

class Comment extends \ActiveRecord\Model {
  static $attr_accessible = Array('fullname', 'email', 'content');
  static $validates_presence_of = Array(
    Array('post_id')
  );
  static $validates_length_of = Array(
    Array('fullname', 'maximum' => 50),
    Array('email', 'maximum' => 60),
  );

  static $belongs_to = Array('post', 'user');

  public function get_fullname() {
    return $this->user_id ?  $this->user->get_fullname() : $this->read_attribute('fullname');
  }

  public function get_email() {
    return $this->user_id ?  $this->user->email() : $this->read_attribute('email');
  }
}

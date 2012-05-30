<?php

/*

+---------+------+--------+------+---------+-------------+----------------+
| Name    | Type | Length | NULL | default | Primary Key | auto increment | 
+---------+------+--------+------+---------+-------------+----------------+
| id      | int  | 11     | no   |         | 1           | 1              | 
+---------+------+--------+------+---------+-------------+----------------+
| post_id | int  | 11     | no   |         |             |                | 
+---------+------+--------+------+---------+-------------+----------------+
| tag_id  | int  | 11     | no   |         |             |                | 
+---------+------+--------+------+---------+-------------+----------------+

*/

namespace Void;

class TagAssign extends \ActiveRecord\Model {
  static $validates_presence_of = Array(
    Array('post_id'),
    Array('tag_id')
  );
  static $validates_length_of = Array(
    Array('post_id', 'maximum' => 11),
    Array('tag_id', 'maximum' => 11)
  );
}

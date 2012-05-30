<?php

/*

+-------------+------+--------+------+---------+-------------+----------------+
| Name        | Type | Length | NULL | default | Primary Key | auto increment | 
+-------------+------+--------+------+---------+-------------+----------------+
| id          | int  | 11     | no   |         | 1           | 1              | 
+-------------+------+--------+------+---------+-------------+----------------+
| post_id     | int  | 11     | no   |         |             |                | 
+-------------+------+--------+------+---------+-------------+----------------+
| category_id | int  | 11     | no   |         |             |                | 
+-------------+------+--------+------+---------+-------------+----------------+

*/

namespace Void;

class CategoryAssign extends \ActiveRecord\Model {
  static $validates_presence_of = Array(
    Array('post_id'),
    Array('category_id')
  );
  static $validates_length_of = Array(
    Array('post_id', 'maximum' => 11),
    Array('category_id', 'maximum' => 11)
  );
}

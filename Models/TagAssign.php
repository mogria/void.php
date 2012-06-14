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
  static $belongs_to = Array(
    Array('tag'),
    Array('post')
  );

  static $validates_uniqueness_of = Array(
    Array(Array('post_id', 'tag_id'))
  );
}

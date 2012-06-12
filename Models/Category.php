<?php

/*

+--------------------+---------+--------+------+---------+-------------+----------------+
| Name               | Type    | Length | NULL | default | Primary Key | auto increment | 
+--------------------+---------+--------+------+---------+-------------+----------------+
| id                 | int     | 11     | no   |         | 1           | 1              | 
+--------------------+---------+--------+------+---------+-------------+----------------+
| name               | varchar | 255    | no   |         |             |                | 
+--------------------+---------+--------+------+---------+-------------+----------------+
| parent_category_id | int     | 11     | yes  |         |             |                | 
+--------------------+---------+--------+------+---------+-------------+----------------+

*/

namespace Void;

class Category extends \ActiveRecord\Model {

  static $attr_accessible = Array('name', 'parent_category_id');

  static $validates_presence_of = Array(
    Array('name')
  );

  static $validates_uniqueness_of = Array('name');

  static $validates_length_of = Array(
    Array('name', 'maximum' => 255)
  );

  static $has_many = Array(
    Array('category_assigns'),
    Array('posts', 'through' => 'category_assigns')
  );
}

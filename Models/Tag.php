<?php

/*

+------+---------+--------+------+---------+-------------+----------------+
| Name | Type    | Length | NULL | default | Primary Key | auto increment | 
+------+---------+--------+------+---------+-------------+----------------+
| id   | int     | 11     | no   |         | 1           | 1              | 
+------+---------+--------+------+---------+-------------+----------------+
| name | varchar | 255    | no   |         |             |                | 
+------+---------+--------+------+---------+-------------+----------------+

*/

namespace Void;

class Tag extends \ActiveRecord\Model {
  static $attr_accessible = Array('name');
  static $validates_presence_of = Array(
    Array('name')
  );
  static $validates_length_of = Array(
    Array('name', 'maximum' => 255)
  );
  static $has_many = Array(
    Array('tag_assigns'),
    Array('posts', 'through' => 'category_assigns')
  );
  static $validates_uniqueness_of = Array(
    Array('name')
  );


  public function set_name($value) {
    $this->assign_attribute('name', self::tagify($value));
  }

  public static function tagify($value) {
    return strtolower(trim($value, " '\""));
  }
}

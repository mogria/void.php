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
  static $validates_presence_of = Array(
    Array('name')
  );
  static $validates_length_of = Array(
    Array('name', 'maximum' => 255)
  );
}
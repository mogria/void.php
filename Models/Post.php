<?php

/*

+------------+----------+--------+------+---------+-------------+----------------+
| Name       | Type     | Length | NULL | default | Primary Key | auto increment | 
+------------+----------+--------+------+---------+-------------+----------------+
| id         | int      | 11     | no   |         | 1           | 1              | 
+------------+----------+--------+------+---------+-------------+----------------+
| title      | varchar  | 255    | no   |         |             |                | 
+------------+----------+--------+------+---------+-------------+----------------+
| content    | text     |        | yes  |         |             |                | 
+------------+----------+--------+------+---------+-------------+----------------+
| created_at | datetime | 19     | yes  |         |             |                | 
+------------+----------+--------+------+---------+-------------+----------------+
| updated_at | datetime | 19     | yes  |         |             |                | 
+------------+----------+--------+------+---------+-------------+----------------+
| user_id    | int      | 11     | no   |         |             |                | 
+------------+----------+--------+------+---------+-------------+----------------+

*/

namespace Void;

class Post extends \ActiveRecord\Model {
  static $attr_accessible = Array('title', 'content');

  static $has_many = Array(
    Array('category_assigns'),
    Array('categories', 'through' => 'category_assigns'),
    Array('tag_assigns'),
    Array('tags', 'through' => 'tag_assigns')
  );

  static $belongs_to = Array('user');

  static $validates_presence_of = Array(
    Array('title'),
    Array('user_id')
  );

  static $validates_length_of = Array(
    Array('title', 'maximum' => 255)
  );
}

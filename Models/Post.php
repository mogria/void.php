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
  static $belongs_to = Array('user');

  static $validates_presence_of = Array(
    Array('title'),
    Array('user_id')
  );
  static $validates_length_of = Array(
    Array('title', 'maximum' => 255),
    Array('created_at', 'maximum' => 19),
    Array('updated_at', 'maximum' => 19),
    Array('user_id', 'maximum' => 11)
  );
}

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
  static $attr_accessible = Array('title', 'content', 'taglist', 'categories');

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

  public function get_taglist() {
    if($this->taglist === null) {
      $tags = $this->tags;
      !$tags && $tags = Array();
      foreach($tags as &$tag) {
        $tag = $tag->name;
        if(strpos($tag, " ") !== false) {
          $tag = '"' . $tag . '"';
        }
      }
      return $this->taglist = implode(", ", $tags);
    } else {
      return $this->taglist;
    }
  }

  public function set_taglist($str) {
    $this->taglist = $str;
  }

  private $taglist;

  static $after_save = Array('create_tags');

  public function create_tags() {
    $tags = explode(",", $this->taglist);
    foreach($tags as $key => $tag) {
      $tag = Tag::tagify($tag);
      $found_tag = Tag::find_by_name($tag);
      if($found_tag === null) {
        $created_tag = new Tag(Array('name' => $tag));
        if($created_tag->save()) {
          $tags[$key] = $created_tag;
        } else {
          unset($tags[$key]);
        }
      } else {
        $tags[$key] = $found_tag;
      }
    }
    TagAssign::table()->delete(Array('post_id' => $this->id));
    foreach($tags as $tag) {
      $attrs = Array('tag_id' => $tag->id, 'post_id' => $this->id);
      TagAssign::create($attrs);
    }
  }

  public function set_categories($ids) {
    !is_array($ids) && $ids = array($ids);

    foreach($ids as $id) {
      $data = Array('post_id' => $this->id, 'category_id' => $id);
      if($this->id != null && !CategoryAssign::find($data)) {
        CategoryAssign::create($data);
      }
    }

    $this->id != null &&
      CategoryAssign::table()->delete(Array('category_id NOT IN (?) AND post_id = ?', $ids, $this->id));
  }
}

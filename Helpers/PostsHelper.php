<?php

namespace Void;
use \ActiveRecord\Model;

class PostsHelper extends ApplicationHelper {
  public function categories($categories) {
    $back = "";
    if(count($categories)) {
      $texts = Array();
      foreach($categories as $category) {
        $texts[] = $category instanceof Model ? $category->name : $category;
      }
      if(count($texts) > 1) {
        $last = array_pop($texts);
        $back = implode(", ", $texts) . " and " . $last;
      } else if(count($texts) === 1) {
        $back = array_shift($texts);
      }
    }
    return $back;
  }
}

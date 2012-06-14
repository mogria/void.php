<?php

namespace Void;

class TagsController extends ApplicationController {

  public function action_index() {
    $this->tags = Tag::all();
  }

  public function action_show($tag = null) {
    $this->tag = Tag::find_by_name($tag);
  }
}

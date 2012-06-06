<?php

namespace Void;
use \Exception;

class CategoriesController extends ApplicationController {

  public function action_index() {
    $this->categories = Category::all();
  }

  public function action_new() {
    $this->category = new Category($_POST);
    if($this->category->save()) {
      Router::redirect('categories', 'show', Array($this->category->id));
    }
  }

  public function action_show($id = null) {
    try {
      $this->category = Category::find($id);
    } catch(Exception $ex) {
      header("404 Not Found");
      $this->category = null;
    }
  }

  public function action_edit($id = null) {
    try {
      $this->category = Category::find($id);
      if($this->category->update_attributes($_POST)) {
        throw Exception();
      }
    } catch(Exception $ex) {
      Router::redirect('category', 'show', Array($id));
    }
  }

  public function action_delete($id = null) {
    try {
      Category::find($id)->delete();
      Flash::success('The category was successfully deleted');
      Router::redirect('categories');
    } catch(Exception $ex) {
    }
  }
}

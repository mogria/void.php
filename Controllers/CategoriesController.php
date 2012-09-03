<?php

namespace Void;
use \Exception;

class CategoriesController extends ApplicationController {

  public function action_index() {
    $this->categories = Category::all();
  }

  public function action_new() {
    $this->category = new Category($_POST);
    if(isset($_POST['form_sent']) && $this->category->save()) {
      Router::redirect_category($this->category->id);
    }
  }

  public function action_show($id = null) {
    try {
      $this->category = Category::find((int)$id);
    } catch(Exception $ex) {
      header("404 Not Found");
      $this->category = null;
    }
  }

  public function action_edit($id = null) {
    try {
      $this->category = Category::find($id);
      if(isset($_POST['form_sent']) && $this->category->update_attributes($_POST)) {
        throw new Exception();
      }
    } catch(Exception $ex) {
      Router::redirect_category($id);
    }
  }

  public function action_delete($id = null) {
    try {
      Category::find($id)->delete();
      Flash::success('The category was successfully deleted');
      Router::redirect_categories();
    } catch(Exception $ex) {
      Router::redirect_categories();
    }
  }
}

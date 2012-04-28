<?php


namespace Void;

use \ActiveRecord\Model;

class FormTag extends Tag {
  protected $contents = Array();
  protected $model = null;
  public function __construct($method, $action, $content = null, Array $attributes = Array()) {
    $this->method = $method;
    parent::__construct("form", $content, $attributes);
  }

  public function setTarget($action) {
    $this->action = $action;
  }

  public function getTarget() {
    return $this->action;
  }

  public function setModel(Model $model) {
    $this->model = $model;
  }

  public function getModel() {
    return $this->model;
  }

  public function __call($method, $params) {
    if($method === 'label') {
      if($this->model !== null) {
        $for = array_shift($params);
        if($for) {
          $attributes = array_shift($params);
          return new LabelTag($this->model, $for , !is_array($attributes) ? array() : $attributes);
        } else {
          // throw exception ?
        }
      }
    } else {
    }
  }
}

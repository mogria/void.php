<?php


namespace Void;

use \ActiveRecord\Model;

class FormTag extends Tag {
  protected $contents = Array();
  protected $model = null;

  protected static $fields = Array(
    'text_field' => 'InputTextTag',
    'password' => 'InputPasswordTag',
    'text_box' => 'InputTextTag'
  );

  public function __construct($method, $action, Array $attributes = Array()) {
    $this->method = $method;
    parent::__construct("form", null, $attributes);
    $this->setTarget($action);
  }

  public function setTarget($action) {
    $this->action = Router::link($action);
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
          return new LabelTag($this->model, $for, $this->grabAttributes($params, 0));
        } else {
          // throw exception ?
        }
      }
    } elseif(array_key_exists(self::$fields, $method)) {
      $classname = self::$fields[$method];
      return new $classname($name, $this->model->$name, $this->grabAttributes($params, 1));
    }
  }

  private function grabAttributes($params, $nr) {
    return isset($params[$nr]) && is_array($params[$nr]) ? $params[$nr] : array();
  }
}

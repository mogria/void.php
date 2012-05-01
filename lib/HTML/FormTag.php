<?php


namespace Void;

use \ActiveRecord\Model;

/**
 * You can create HTML Forms using this class
 *
 */
class FormTag extends Tag {
  protected $contents = Array();
  protected $model = null;

  protected static $fields = Array(
    'text_field' => 'InputTextTag',
    'password'   => 'InputPasswordTag',
    'text_area'  => 'TextareaTag'
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
      $name = $this->model;
      if($name === null) {
        $name = array_shift($params);
      }
      $for = array_shift($params);
      if($for) {
        $tag = new LabelTag($name, $for, $this->grabAttributes($params, 0));
        return $tag->output();
      }
    } elseif($method === 'submit') {
      $tag = new InputSubmitTag(array_shift($params), $this->grabAttributes($params, 0));
      return $tag->output();
    } elseif(array_key_exists($method, self::$fields)) {
      $classname = __NAMESPACE__ . "\\" . self::$fields[$method];
      $tag = new $classname($name = array_shift($params), $this->model === null ? "" : $this->model->$name, $this->grabAttributes($params, 0));
      return $tag->output();
    }
  }

  private function grabAttributes($params, $nr) {
    return isset($params[$nr]) && is_array($params[$nr]) ? $params[$nr] : array();
  }
}

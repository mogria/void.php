<?php

namespace Void\HTML;

use \ActiveRecord\Model;
use \Void\Router;
use \BadMethodCallException;

/**
 * You can create HTML Forms using this class
 *
 */
class FormTag extends Tag {
  protected $contents = Array();
  protected $model = null;

  protected static $buttons = Array(
    'submit' => 'InputSubmitTag'
  );
  protected static $normal_fields = Array(
    'text_field'     => 'InputTextTag',
    'password_field' => 'InputPasswordTag',
    'check_box'      => 'InputCheckboxTag',
    'text_area'      => 'TextareaTag'
  );

  protected static $multiple_fields = Array(
    'radio_button'   => 'InputRadioTag',
    'select'         => 'SelectTag'
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
    } else {
      $type = "";
      $tag;
      if(array_key_exists($method, self::$buttons)) {
        $classname = __NAMESPACE__ . "\\" . self::$buttons[$method];
        $tag = new InputSubmitTag(array_shift($params), $this->grabAttributes($params, 0));
      } elseif(array_key_exists($method, self::$normal_fields)) {
        $classname = __NAMESPACE__ . "\\" . self::$normal_fields[$method];
        $tag = new $classname($name = array_shift($params), $this->model === null ? "" : $this->model->$name, $this->grabAttributes($params, 0));
      } elseif(array_key_exists($method, self::$multiple_fields)) {
        $classname = __NAMESPACE__ . "\\" . self::$multiple_fields[$method];
        $tag = new $classname($name = array_shift($params), array_shift($params), $this->grabAttributes($params, 0));
      } else {
        throw new BadMethodCallException("no such form element '$method'");
      }
      return $tag->output();
    }
  }

  private function grabAttributes($params, $nr) {
    return isset($params[$nr]) && is_array($params[$nr]) ? $params[$nr] : array();
  }
}

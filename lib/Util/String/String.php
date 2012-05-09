<?php

namespace Void;

class String {
  protected $data;
  public function __construct($string) {
    $this->data = (string)$string;
  }

  public function camelize() {
    $this->data = preg_replace_callback("/(?:_|^)([a-z])/i", function($match) {
      return strtoupper($match[1]);
    }, $this->data);
    return $this;
  }

  public function uncamelize() {
    $this->data = ltrim(preg_replace_callback("/([A-Z])/", function($match) {
      return "_" . strtolower($match[1]);
    }, $this->data), "_");
    return $this;
  }

  public function __toString() {
    return $this->data;
  }

  protected static $string_funcs = Array(
    'replace' => Array('str_replace', 3, true),
    'ireplace' => Array('str_ireplace', 3, true),
    'preg_replace' => Array('preg_replace', 3, true),
    'preg_replace_callback' => Array('preg_replace_callback', 3, true),
    'pos' => Array('strpos', 1, false),
    'ipos' => Array('stripos', 1, false),
    'rpos' => Array('strrpos', 1, false),
    'ripos' => Array('strripos', 1, false),
    'substr' => Array('substr', 1, true),
    'explode' => Array('explode', 2, false),
    'split' => Array('str_split', 1, false),
    'repeat' => Array('str_repeat', 1, true)
  );

  public function __call($method, $args) {
    if(!isset(self::$string_funcs[$method])) {
      throw new BadMethodCallException();
    }

    $args = array_slice($args, 0, self::$string_funcs[$method][1] - 1)
            + array($this->data)
            + array_slice($args, self::$string_funcs[$method][1]);
    $back = call_user_func_array(self::$string_funcs[$method][0], $args);

    if(self::$string_funcs[$method][2]) {
      $this->data = (string)$back;
      return $this;
    } else {
      return $back;
    }
  }
}

function s($data) {
  return new String($data);
}

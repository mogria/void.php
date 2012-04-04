<?php

namespace Void;

class Debug {
  public static function out($value) {
    var_dump($value);
    return $value;
  }
}

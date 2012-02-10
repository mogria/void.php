<?php

namespace Void;

class UcFirstFilter implements Filter {
  public function filter($value) {
    return is_string($value) && strlen($value) > 0 && $value{0} == strtoupper($value{0});
  }
}
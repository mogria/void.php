<?php

namespace Void;

class Hash extends VoidBase {
  public static function create($value, $salt = null) {
    $salt === null && $salt = self::generateSalt();
    for($i = 0; $i < self::$config->iterations; $i++) {
      $value = hash(self::$config->algo, $value. self::$config->secret . substr($salt, $i % 2 ? 16 : 0, 16));
    }
    return $value . ":" . $salt;
  }

  public static function generateSalt() {
    return md5(mt_rand() . microtime() . self::$config->secret . time() . mt_rand());
  }

  public static function compare($value, $hash) {
    $salt = array_slice(explode(":", $hash), 1);
    $salt = array_shift($salt);
    return self::create($value, $salt) === $hash;
  }
}

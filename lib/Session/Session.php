<?php

namespace Void;

/**
 * Session class for managing sessions
 *
 * You can use this as follows:
 *
 * Session::user(); -> // returns the contents of $_SESSION['user']
 * Session::user("Mogria"); -> // setsthe contents of $_SESSION['user'] to "Mogria"
 * Session::user(); -> // setsthe contents of $_SESSION['user'] to "Mogria"
 *
 */
class Session extends VoidBase {
  public static function __callStatic($method, $args) {
    $back = null;
    if(count($args) > 0) {
      $back = self::set($method, array_shift($args));
    } else {
      if($ending = "exists" && self::ends_with($method, $ending)) {
        $back = self::exists($method);
      } else if($ending = "delete" && self::ends_with($method, $ending)) {
        $back = self::delete($method);
      } else {
        $back = self::get($method);
      }
    }
    return $back;
  }

  public static function set($key, $value) {
    return $_SESSION[$key] = $value;
  }

  public static function get($key) {
    return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
  }

  public static function exists($key) {
    return array_key_exists($key, $_SESSION);
  }

  public static function delete($key) {
    if($back = static::exists($key)) {
      unset($_SESSION[$key]);
    }
    return $back;
  }

  public static function ends_with(&$str, &$ending) {
    $real_ending = substr($str, -strlen($ending));
    if(strtolower($real_ending) == strtolower($ending)) {
      $ending = $real_ending;
      $str = rtrim(substr($str, 0, -strlen($ending)), "_");
      return true;
    } else {
      return false;
    }
  }
}


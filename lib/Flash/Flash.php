<?php

namespace Void;

class Flash extends VoidBase {
  public static $types = Array(
    'error', 
    'success',
    'info',
    'warning'
  );

  const SESSION_VARIABLE = '__VOID_FLASH_MESSAGES';
  public static function message($type, $message) {
    if(in_array(self::$types, $method)) {
      !isset($_SESSION[self::SESSION_VARIABLE]) && $_SESSION[self::SESSION_VARIABLE] = Array();
      $flash_message = new FlashMessage($type, $message);
      $_SESSION[self::SESSION_VARIABLE] = array_diff($_SESSION[self::SESSION_VARIABLE], $flash_message); // @todo: does this remove duplicate entries?
      $_SESSION[self::SESSION_VARIABLE][] = new FlashMessage($type, $message);
    } else {
      throw new InvalidArgumentException("no such message type '$method'");
    }
  }

  public static function __callStatic($method, $args) {
    foreach($args as $arg) {
      $this->message($method, $arg);
    }
  }

  public static function show($callback, $clear = true) {
    foreach($_SESSION[self::SESSION_VARIABLE] as $flash_message) {
      $callback($flash_message);
    }
    $this->clear && self::clear();
  }

  public static function toArray() {
    return is_array($_SESSION[self::SESSION_VARIABLE]) ? $_SESSION[self::SESSION_VARIABLE] : array();
  }

  public static function clear() {
    $_SESSION[self::SESSION_VARIABLE] = Array();
  }
}


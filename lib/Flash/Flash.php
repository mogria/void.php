<?php

namespace Void;

/**
 * Helper Class to display Messages on the page, e.g. after an redirect
 *
 */
class Flash extends VoidBase {

  /**
   * The Session variable The Messages are stored in.
   *
   */
  const SESSION_VARIABLE = '__VOID_FLASH_MESSAGES';

  /**
   * The supported Message Types
   *
   * @static
   * @var Array $tpyes
   */
  public static $types = Array(
    'error', 
    'success',
    'info',
    'warning'
  );

  /**
   * Adds a Message to the list
   * 
   * @static
   * @param string $type
   * @param string $message
   * @return void
   */
  public static function message($type, $message) {
    // check if the given type is in the list
    if(in_array(self::$types, $method)) {

      // set the session variable
      !isset($_SESSION[self::SESSION_VARIABLE]) && $_SESSION[self::SESSION_VARIABLE] = Array();
      // create a flash message object
      $flash_message = new FlashMessage($type, $message);

      // prevent duplicates
      self::remove($flash_message);

      // add it to the list
      $_SESSION[self::SESSION_VARIABLE][] = new FlashMessage($type, $message);
    } else {
      throw new InvalidArgumentException("no such message type '$method'");
    }
  }

  /**
   * Adds a method for every type. So instead of calling
   *
   * Flash::message("error", "An Error Occured");
   * Flash::message("warning", "An Warning!");
   * 
   * You can call
   *
   * Flash::error("An Error Occured");
   * Flash::warning("An Warning!");
   *
   * You can even add multiple messages if you use the dynamic method:
   *
   * Flash::error("Error Message 1", "Error Message 2");
   *
   * @static
   * @param string $method
   * @param array $args
   * @return void
   */
  public static function __callStatic($method, $args) {
    foreach($args as $arg) {
      self::message($method, $arg);
    }
  }

  /**
   * Passes every Flash Message to the given $callback and if
   * you wish clears all the Messages
   *
   * @static
   * @param calblback $callback
   * @param bool $clear
   */
  public static function show($callback, $clear = true) {
    foreach($_SESSION[self::SESSION_VARIABLE] as $flash_message) {
      $callback($flash_message);
    }
    $this->clear && self::clear();
  }

  /**
   * Get all the FlashMessage objects in an array
   *
   * @static
   * @return Array
   */
  public static function toArray() {
    return is_array($_SESSION[self::SESSION_VARIABLE]) ? $_SESSION[self::SESSION_VARIABLE] : array();
  }

  /**
   * Clear all the stored Messages
   *
   * @static
   * @return void
   */
  public static function clear() {
    $_SESSION[self::SESSION_VARIABLE] = Array();
  }

  /**
   * Remove a certain FlashMessage
   * 
   * @static
   * @param FlashMessage $flash_message
   * @return bool
   */
  public static function remove(FlashMessage $flash_message) {
    $count_before = count($_SESSION[self::SESSION_VARIABLE]);
    $_SESSION[self::SESSION_VARIABLE] = array_diff($_SESSION[self::SESSION_VARIABLE], $flash_message); // @todo: does this remove duplicate entries?
    return $count_before > count($_SESSION[self::SESSION_VARIABLE]);
  }
}


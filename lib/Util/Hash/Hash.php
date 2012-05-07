<?php

namespace Void;

/**
 * A class which helps generating and comparing Hashes
 */
class Hash extends VoidBase {
  /**
   * create a hash
   * if no $salt is given a new one is created
   *
   * @static
   * @param string $value   - the value to hash
   * @param string $salt    - the salt which should be used
   * @return string  - with the format $hash:$salt
   */
  public static function create($value, $salt = null) {
    // generate a salt if none is given
    $salt === null && $salt = self::generateSalt();
    
    // multiple iterations (make things slower but more secure)
    for($i = 0; $i < self::$config->iterations; $i++) {
      $value = hash(self::$config->algo, $value. self::$config->secret . substr($salt, $i % 2 ? 16 : 0, 16));
    }
    return $value . ":" . $salt;
  }

  /**
   * create a salt
   *
   * @static
   * @return string
   */
  public static function generateSalt() {
    return md5(mt_rand() . microtime() . self::$config->secret . time() . mt_rand());
  }

  /**
   * compares a value with a hash
   * returns true if $value results in the same hash
   * returns false if not
   *
   * @param string $value
   * @param string $hash
   * @return bool
   */
  public static function compare($value, $hash) {
    // get the salt from the hash
    $salt = array_slice(explode(":", $hash), 1);
    $salt = array_shift($salt);
    // create a new hash out of the given $value & the salt and see if they're the same
    return self::create($value, $salt) === $hash;
  }
}

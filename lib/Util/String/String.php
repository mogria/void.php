<?php

namespace Void;
use \ArrayAccess;
use \InvalidArgumentException;

/***
 * This represents a string. This adds some functionality to the string,
 * and also lets you chain certain methods
 *
 */
class String implements ArrayAccess {

  /**
   * contains the string
   * @var string $data
   */
  protected $data;

  /**
   * an array which defines which intern php gets called when you call certain 
   * methods on this object
   * @var Array $string_funcs
   */
  protected static $string_funcs = Array(
    'replace' => Array('str_replace', 3, true),
    'ireplace' => Array('str_ireplace', 3, true),
    'match' => Array('preg_match', 2, false),
    'match_all' => Array('preg_match_all', 2, false),
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

  /**
   * Constructor
   * @param string $string - the string data
   */
  public function __construct($string) {
    $this->data = (string)$string;
  }

  /**
   * camelizes the intern string data
   * For Example:
   * test          -> Test       
   * test_string   -> TestString
   * a_test_string -> ATestString
   *
   * @return String - this
   */
  public function camelize() {
    $this->data = preg_replace_callback("/(?:_|^)([a-z])/i", function($match) {
      return strtoupper($match[1]);
    }, $this->data);
    return $this;
  }

  /**
   * uncamelizes the intern string data
   * For Example:
   * Test        -> test         
   * TestString  -> test_string  
   * ATestString -> a_test_string
   *
   * @return String - this
   */
  public function uncamelize() {
    $this->data = ltrim(preg_replace_callback("/([A-Z])/", function($match) {
      return "_" . strtolower($match[1]);
    }, $this->data), "_");
    return $this;
  }

  /**
   * returns the string data
   * @return string - the intern string
   */
  public function __toString() {
    return $this->data;
  }

  /**
   * calls an intern php function with the given arguments
   * 
   * For Example:
   * $string = new String("abc");
   * echo $string->replace('a', 'b'); // calls str_replace() & outputs "bbc"
   */
  public function __call($method, $args) {
    if(!isset(self::$string_funcs[$method])) {
      throw new \BadMethodCallException();
    }

    $args = array_merge(
      array_slice($args, 0, self::$string_funcs[$method][1] - 1),
      array($this->data),
      array_slice($args, self::$string_funcs[$method][1] - 1)
    );
    $back = call_user_func_array(self::$string_funcs[$method][0], $args);

    if(self::$string_funcs[$method][2]) {
      $this->data = (string)$back;
      return $this;
    } else {
      return $back;
    }
  }

  /**
   * A little Helper method to make references work with __call()
   *
   */
  public function match_all($pattern, &$matches, $flags = 0) {
    $args = array($pattern, &$matches, $flags);
    return $this->__call('match_all', $args);
  }

  const REGEX_OFFSET = '/^(-?[0-9]+)?(:)?(-?[0-9]+?)?$/D';

  /* implements the ArrayAccess interface. Make the strings 'python'-like */
  public function offsetGet($offset) {
    if(!$this->offsetExists($offset)) {
      throw new InvalidArgumentException("offset has to be a number or in format " . self::REGEX_OFFSET);
    }
    $matches = Array();
    s($offset)->match_all(self::REGEX_OFFSET, $matches);
    $matches = array_slice($matches, 1);
    
    $colon = $matches[1][0];
    $number1 = $matches[0][0];
    $number2 = $matches[2][0];
    $clone = clone $this;
 
    if($colon === ":") {
      if($number1 === '' && $number2 === '')  {
      } else if($number1 === '') {
        $clone->substr(0, $number2);
      } else if($number2 === '') {
        $clone->substr($number1);
      } else {
        $number1 < 0 && $number1 = strlen($this->data) + $number1;
        $number2 < 0 && $number2 = strlen($this->data) + $number2;
        if($number1 > $number2) {
          $tmp = $number1;
          $number1 = $number2;
          $number2 = $tmp;
        }
        $clone->substr($number1, $number2 - $number1);
      }
    } else {
      $clone->substr($number1, 1);
    }
    return $clone;
  }

  public function offsetSet($offset, $value) {
  }

  public function offsetUnset($offset) {
  }

  public function offsetExists($offset) {
    $offset = (string)$offset;
    return $offset != '' && (bool)s($offset)->match(self::REGEX_OFFSET);
  }
}

/**
 * A shortcut `new String()`
 *
 * For Example:
 * s("string");
 * 
 * is the same as
 *
 * new String("string");
 *
 */
function s($data) {
  return new String($data);
}

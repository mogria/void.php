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
    'repeat' => Array('str_repeat', 1, true),
    'length' => Array('strlen', 1, false)
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

  /* some aliases */
  public function len() {
    return $this->__call('length', array());
  }

  public function substring($pos1, $pos2 = null) {
    $args = array($pos1, $pos2);
    return $this->__call('substr', $args);
  }

  const REGEX_OFFSET = '/^(-?[0-9]+)?(:)?(-?[0-9]+?)?$/D';

  /* implements the ArrayAccess interface. Make the strings 'python'-like */
  public function offsetGet($offset) {
    $pos1 = $pos2 = null;
    $this->parseOffset($offset, $pos1, $pos2);

    $clone = clone $this;
    return $clone->substr($pos1, $pos2 - $pos1);
  }

  public function offsetSet($offset, $value) {
    $pos1 = $pos2 = null;
    $this->parseOffset($offset, $pos1, $pos2);
      
    $this->data = substr($this->data, 0, $pos1) . $value . substr($this->data, $pos2);
    return $this;
  }

  public function offsetUnset($offset) {
    $this->offsetSet($offset, "");
    return $this;
  }

  public function offsetExists($offset) {
    $offset = (string)$offset;
    return $offset != '' && (bool)s($offset)->match(self::REGEX_OFFSET);
  }

  public function checkOffsetExists($offset) {
    if(!$this->offsetExists($offset)) {
      throw new InvalidArgumentException("offset has to be a number or in format " . self::REGEX_OFFSET);
    }
  }

  private function parseOffset($offset, &$pos1, &$pos2) {
    $this->checkOffsetExists($offset);
    $matches = Array();
    s($offset)->match_all(self::REGEX_OFFSET, $matches);
    $matches = array_slice($matches, 1);
    
    $colon = $matches[1][0];
    $pos1 = $matches[0][0];
    $pos2 = $matches[2][0];
    $this->toPositiveOffset($pos1, $pos2, $colon);
  }

  private function toPositiveOffset(&$pos1, &$pos2, $colon) {
    $length = $this->length();

    $make_positive = function($value) use ($length) {
      // resolve the relative negative offset
      $value = $value != '' && $value < 0 ? $length + $value : $value;
      // value may still be negative, fix that
      return $value < 0 ? 0 : $value;
    };

    // no negative offsets like -1, -15 etc.
    $pos1 = $make_positive($pos1);
    $pos2 = $make_positive($pos2);

    // position1 should be lower than position 2
    if($pos1 !== '' && $pos2 !== '' && $pos1 > $pos2) {
      $tmp = $pos1;
      $pos1 = $pos2;
      $pos2 = $tmp;
    }

    // if nothing set, set position 1 to 0 
    if($pos1 === '') {
      $pos1 = 0;
    }

    // if a colon is given two positions are given, if not only 1 (an index)
    if($colon) {
      // set position 2 to the length of the string if nothing is given
      if($pos2 === '') {
        $pos2 = $length;
      }
    } else {
      // if only 1 position is given, position 2 has to be one more than position 1 
      $pos2 = $pos1 + 1;
    }

    $pos1 = (int)$pos1;
    $pos2 = (int)$pos2;
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

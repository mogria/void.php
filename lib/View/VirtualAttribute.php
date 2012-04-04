<?php

namespace Void;
/**
 * @author Mogria
 */

/**
 * An Simple object which is an array and a Iterator at the same time
 * You can set and get what property you ever want
 *
 */
class VirtualAttribute extends VoidBase implements \ArrayAccess, \IteratorAggregate {
  /**
   * Array where all the properties are stored
   * @var Array $__virtual_vars
   */
  protected $__virtual_vars = Array();

  /**
   * Constructor
   *
   * @param Array $reference
   */
  public function __construct(&$reference) {
    $this->setReference($reference);
  }

  public function setReference(&$reference) {
    $this->__virtual_vars = &$reference;
  }

  /**
   * Throws an Exception if the $key isn't defined
   *
   * @param $key
   */
  protected function isUndefinedProperty($key) {
    if(!$this->exists($key)) {
        throw new UndefinedPropertyException("property $key does not exist!");
    }
  }

  /**
   * Returns a Reference to the value of $key
   *
   * @param $key
   * @return mixed
   */
  public function get($key) {
    $this->isUndefinedProperty($key);
    return $this->__virtual_vars[$key];
  }

  /**
   * sets the value of $key
   *
   * @param string $key
   * @param mixed $value
   * @return mixed
   */
  public function set($key, $value) {
    return $this->__virtual_vars[$key] = $value;
  }

  /**
   * sets multiple values
   *
   * @param Array $array
   */
  public function setArray(Array $array) {
    foreach($array as $key => $value) {
      $this->set($key, $value);
    }
  }

  /**
   * checks if $key exists
   *
   * @param string $key
   * @return bool
   */
  public function exists($key) {
    return array_key_exists($key, $this->__virtual_vars);
  }

  /**
   * Removes the $key
   *
   * @param string $key
   */
  public function delete($key) {
    $this->isUndefinedProperty($key);
    unset($this->__virtual_vars[$key]);
  }

  /**
   * Converts this object to an Array
   *
   * @return Array
   */
  public function toArray() {
    return $this->__virtual_vars;
  }

  /**
   * Returns a Reference of the array all the variables are stored in
   *
   * @return &Array
   */
  public function &getReference() {
    return $this->__virtual_vars;
  }

  /* ArrayAccess */

  /*
   * The following four method's are declared in the ArrayAccess
   * and mapped to the corresponding functions in this class
   */
  public function offsetExists($offset) {
    return $this->exists($offset);
  }

  public function offsetGet($offset) {
    return $this->get($offset);
  }

  public function offsetSet($offset, $value) {
    return $this->set($offset, $value);
  }

  public function offsetUnset($offset) {
    $this->delete($offset);
  }

  /* IteratorAggregate */
  public function getIterator() {
    return ArrayIterator($this->__virtual_vars);
  }

  /* Magic Methods */


  /*
   * magic function for easier access
   */
  public function __get($key) {
    return $this->get($key);
  }
  public function __set($key, $value) {
    if($key === "_" && is_array($value)) {
      $this->setArray($value);
      return;
    }
    return $this->set($key, $value);
  }

  public function __isset($key) {
    return $this->exists($key);
  }

  public function __unset($key) {
    $this->delete($key);
  }

}

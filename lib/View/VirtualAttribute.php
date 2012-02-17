<?php

namespace Void;
/**
 * @author Mogria
 */

class VirtualAttribute implements \ArrayAccess, \IteratorAggregate {
  protected $__virtual_vars;

  public function __construct(&$reference) {
    $this->__virtual_vars = &$reference;
  }
  
  protected function isUndefinedProperty($key) {
    if(!$this->exists($key)) {
        throw new UndefinedPropertyException();
    }
  }

  public function &get($key) {
    $this->isUndefinedProperty($key);
    return $this->__virtual_vars[$key];
  }
  
  public function set($key, $value) {
    return $this->__virtual_vars[$key] = $value;
  }
  
  public function setArray(Array $array) {
    foreach($array as $key => $value) {
      $this->set($key, $value);
    }
  }

  public function exists($key) {
    return array_key_exists($key, $this->__virtual_vars);
  }
  
  public function delete($key) {
    $this->isUndefinedProperty($key);
    unset($this->__virtual_vars[$key]);
  }
  
  public function toArray() {
    return $this->__virtual_vars;
  }
  
  public function &getReference() {
    return $this->__virtual_vars;
  }
  
  /* ArrayAccess */
  
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

  public function &__get($key) {
    return $this->get($key);
  }

  public function __set($key, $value) {
    if($key === "_" && is_array($value)) {
      $this->setArray($value);
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
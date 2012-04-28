<?php

namespace Void;

/**
 * This represents an HTML Tag.
 *
 * @author Mogria
 * @package void.php
 */
class Tag extends VirtualAttribute {

  /**
   * The name of the HTML Tag
   * @var string
   */
  protected $name;

  /**
   * the content of the HTML Tag
   * @var string
   */
  protected $content;

  /**
   * Constructor
   *
   * @param string $name
   * @param string $content
   * @param Array $attributes
   */
  public function __construct($name, $content = null, Array $attributes = Array()) {
    $this->setName($name);
    $this->setContent($content);
    $this->_ = $attributes;
  }

  /**
   * setter for property name
   *
   * @param string $name
   */
  public function setName($name) {
    $this->name = strtolower($name);
  }

  /**
   * getter for property name
   *
   * @return string $name
   */
  public function getName() {
    return $this->name;
  }

  /**
   * setter for property content
   *
   * @param string $content
   */
  public function setContent($content) {
    $this->content = $content;
  }

  /**
   * getter for property content
   *
   * @return string $content
   */
  public function getContent() {
    return $this->content;
  }

  /**
   * returns the HTML of the tag
   *
   * @return string
   */
  public function output() {
    $attributes = $this->getReference();
    array_walk($attributes, function(&$value, $key) {
      $value = " " . htmlspecialchars($key) . ( $value !== null ? "=\"" . htmlspecialchars($value) . "\"" : "");
    });
    return "<" . $this->name . implode("", $attributes) . ($this->content !== null ? ">" . $this->content . "</" . $this->name . ">" : " />");
  }

  /**
   * magic method which is called when this object is converted to a string
   *
   * @return string
   */
  public function __toString() {
    return $this->output();
  }

  public function __get($name, $value) {
    if(method_exists($this, $method = 'get' . ucfirst($name)) {
      return $this->$method($value);
    } else {
      return parent::__get($name, $value);
    }
  }

  public function __set($name, $value) {
    if(method_exists($this, $method = 'get' . ucfirst($name)) {
      return $this->$method($value);
    } else {
      return parent::__get($name, $value);
    }
  }
}

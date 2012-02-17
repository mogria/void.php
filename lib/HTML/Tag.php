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
    $this->name = $name;
    $this->_ = $attributes;
  }

  /**
   * setter for property name
   *
   * @param string $name
   */
  public function setName($name) {
    $this->name = $name;
  }

  /**
   * getter for property name
   *
   * @return string $name
   */
  public function getName($name) {
    return $this->name;
  }

  /**
   * setter for property content
   *
   * @param string $content
   */
  public function setContent($content) {
    return $this->content;
  }

  /**
   * getter for property content
   *
   * @return string $content
   */
  public function getContent($content) {
    return $this->content;
  }

  /**
   * returns the HTML of the tag
   *
   * @return string
   */
  public function output() {
    return "<" . $this->name . implode("", array_map(function(&$value, $key) {
      $value = " " . htmlspecialchars($key) . ( $value !== null ? "=\"" . htmlspecialchars($value) . "\"" : "")
    }, $this->getArray())) . ">" . ($this->content !== null ? $this->content . "</" . $this->name . ">" : " />");
  }

  /**
   * magic method which is called when this object is converted to a string
   *
   * @return string
   */
  public functio __toString() {
    return $this->output();
  }
}
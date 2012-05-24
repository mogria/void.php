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
  protected $tagname;

  /**
   * the content of the HTML Tag
   * @var string
   */
  protected $content;

  /**
   * Constructor
   *
   * @param string $tagname
   * @param string $content
   * @param Array $attributes
   */
  public function __construct($tagname, $content = null, Array $attributes = Array()) {
    $this->setTagname($tagname);
    $this->setContent($content);
    $this->_ = $attributes;
  }

  /**
   * setter for property tagname
   *
   * @param string $tagname
   */
  public function setTagname($tagname) {
    $this->tagname = strtolower($tagname);
  }

  /**
   * getter for property tagname
   *
   * @return string $tagname
   */
  public function getTagname() {
    return $this->tagname;
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
    if($this->getTagname() == "") {
      return $this->getContent();
    }
    $attributes = $this->getReference();
    array_walk($attributes, function(&$value, $key) {
      $value = " " . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . ( $value !== null ? "=\"" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "\"" : "");
    });
    return "<" . $this->tagname . implode("", $attributes) . ($this->content !== null ? ">" . $this->getContent(). "</" . $this->tagname . ">" : " />");
  }

  /**
   * magic method which is called when this object is converted to a string
   *
   * @return string
   */
  public function __toString() {
    return $this->output();
  }
}

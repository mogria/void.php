<?php

namespace Void;

/**
 * This represents a HTML <a>-Tag
 */
class ATag extends Tag {
  /**
   * Constructor
   *
   * @access public
   * @param string $content   - null or the text in the <a>-Tag
   * @param string $target    - an link, or an array which gets passed
   *                            to Router::link() (a link within this application)
   * @param Array $attributes - additional attributes to set (href will be overwritten)
   */
  public function __construct($content = null, $target = '#', Array $attributes = Array()) {
    parent::__construct("a", $content, $attributes);
    // if we get an array in the $target param, pass it to the Router::link() method and generate a link within this application
    is_array($target) && $target = Router::link($target);
    // put the link into the href="" attribute
    $this->setTarget($target);
  }

  /**
   * set the contents of the href="" attribute
   *
   * @access public
   * @param string $link
   */
  public function setTarget($link) {
    // put the link into the href="" attribute
    $this->href = $link;
  }

  /**
   * returns the contents of the href="" attribute
   *
   * @access public
   * @return string
   */
  public function getTarget() {
    // return the contents of the href attribute
    return $this->href;
  }
}

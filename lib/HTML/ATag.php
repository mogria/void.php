<?php

namespace Void\HTML;
use Void\Router;

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
   * @param Array $attributes - additional attributes to set (href will be overwritten)
   */
  public function __construct($content = null, $target = '#', Array $attributes = Array()) {
    parent::__construct("a", $content, $attributes);
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

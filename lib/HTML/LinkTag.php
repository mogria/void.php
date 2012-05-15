<?php

namespace Void;

/**
 * represents a HTML <link>-Tag
 */
class LinkTag extends Tag {
  /**
   * Constructor
   *
   * @access public
   * @param array $src        - the path of the css file
   *                          - (relative to the stylesheets/ folder
   * @param Array $attributes - additional attributes
   */
  public function __construct($href = null, Array $attributes = Array()) {
    $this->type = "text/css";
    $this->rel = "stylesheet";
    parent::__construct("link", null, $attributes);
    $this->setTarget($href);
  }

  /**
   * set the contents of the href="" attribute
   *
   * @access public
   * @param string $link
   */
  public function setTarget($link) {
    $this->href =  (strpos($link, BASEURL) !== 0) ? BASEURL . $link : $link;
  }

  /**
   * returns the contents of the href="" attribute
   *
   * @access public
   * @return string
   */
  public function getTarget() {
    return $this->href;
  }
}

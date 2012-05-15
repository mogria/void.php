<?php

namespace Void;

/**
 * represents a HTML <script>-Tag
 */ 
class ScriptTag extends Tag {
  /**
   * Constructor
   *
   * @access public
   * @param array $src        - the path of the javascript file
   *                          - (relative to the javascripts/ folder
   * @param Array $attributes - additional attributes
   */
  public function __construct($src = null, Array $attributes = Array()) {
    $this->type = "text/javascript";
    parent::__construct("script", " ", $attributes);
    $this->setTarget($src);
  }

  /**
   * sets the src=""-attribute of the <script>-Tag
   *
   * @access public
   * @param string $link - the link to a javascript file
   */
  public function setTarget($link) {
    $this->src = (strpos($link, BASEURL) !== 0) && (strpos($link, FULLURL) !== 0) ? BASEURL . $link : $link;
  }

  /**
   * returns the contents of the src="" attribute
   *
   * @access public
   * @return string
   */
  public function getTarget() {
    return $this->src;
  }
}

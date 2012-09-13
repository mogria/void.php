<?php

namespace Void\Response\Format;
use Void\Response\Format;


/**
 * Text format for an HTTP response
 */
class Text implements Format {

  /**
   * content
   * @var string
   */
  protected $content;

  /**
   * Constructor
   *
   * can be used to set the content
   */
  public function __construct($content = "") {
    $this->content = $content;
  }

  /**
   * returns the mime type of the format
   */
  public function getMimeType() {
    return 'text/plain';
  }

  /**
   * returns the file extension of an format
   */
  public function getFileExtenison() {
    return 'txt';
  }

  /**
   * formats the content properly to be sent to the browser
   *
   * in case of a text file: do nothing with the content :-)
   */
  public function format() {
    return $this->content;
  }

  /**
   * setter for $content property
   *
   * @param string $content
   */
  public function setContent($content) {
    $this->content = $content;
  }

  /**
   * getter for $content property
   * 
   * @return string
   */
  public function getContent() {
    return $this->content;
  }
}

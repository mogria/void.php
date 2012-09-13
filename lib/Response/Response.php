<?php

namespace Void;

/**
 * this represents an HTTP response
 *
 */
class Response {

  /**
   * the format of the response (like HTML, text, etc..)
   * @var Response\Format
   */
  protected $format;

  /**
   * Constructor
   * sets default response type to Text
   */
  public function __construct() {
    $this->setFormat(new Response\Format\Text());
  }

  /**
   * change the format of the 
   * @param Response\Format $format
   */
  public function setFormat(Response\Format $format) {
    $this->format = $format;
  }

  /**
   * getter for property $format
   *
   * @return Response\Format
   */
  public function getFormat() {
    return $this->format;
  }
  
  /**
   * sends the response to the browser
   *
   * this includes additional headers & the content of the site
   * @return void
   */
  public function send() {
    // set headers
    header('Content-Type: ' . $this->format->getMimeType());

    // output the response
    echo $this->format->format();
    flush();
  }
}

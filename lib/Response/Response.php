<?php

namespace Void;

class Response {

  protected $format;

  protected $content;

  public function __construct() {
    $this->setFormat(new Response\Format\Text());
  }

  public function setFormat($format) {
    $this->format = $format;
  }

  public function getFormat() {
    return $this->format;
  }

  public function setContent($content) {
    $this->content = $content;
  }

  public function getContent() {
    return $this->content;
  }
  
  public function send() {
    // set headers
    header('Content-Type: ' . $this->format->getMimeType());

    // output the response
    echo $this->content;
  }
}

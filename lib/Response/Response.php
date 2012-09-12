<?php

namespace Void;

class Response {

  protected $format;

  public function __construct() {
    $this->setFormat(new Response\Format\Text(""));
  }

  public function setFormat(Response\Format $format) {
    $this->format = $format;
  }

  public function getFormat() {
    return $this->format;
  }
  
  public function send() {
    // set headers
    header('Content-Type: ' . $this->format->getMimeType());

    // output the response
    echo $this->format->format();
  }
}

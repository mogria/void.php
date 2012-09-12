<?php

namespace Void\Response\Format;
use Void\Response\Format;


class Text implements Format {
  protected $content;

  public function __construct($content) {
    $this->content = $content;
  }

  public function getMimeType() {
    return 'text/plain';
  }

  public function getFileExtenison() {
    return 'txt';
  }

  public function format() {
    return $response->getContent();
  }

  public function setContent($content) {
    $this->content = $content;
  }

  public function getContent() {
    return $this->content;
  }
}

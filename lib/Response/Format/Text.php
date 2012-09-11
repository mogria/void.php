<?php

namespace Void\Response\Format;
use Void\Response\Format;


class Text implements Format {
  public function getMimeType() {
    return 'text/plain';
  }

  public function getFileExtenison() {
    return 'txt';
  }
}

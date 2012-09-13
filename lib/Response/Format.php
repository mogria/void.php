<?php

namespace Void\Response;

/**
 * interface which describes a format of an HTTP response
 */
interface Format {
  public function getMimeType();
  public function getFileExtenison();
  public function format();
}

<?php

namespace Void\Response;

interface Format {
  public function getMimeType();
  public function getFileExtenison();
  public function format();
}

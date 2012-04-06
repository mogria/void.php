<?php

namespace Void;

class CSSAsset extends Asset {
  public function __construct($main_file = "application", $folder = "stylesheets", $extension = "css") {
    parent::__construct($folder, $extension, $main_file);
  }
}

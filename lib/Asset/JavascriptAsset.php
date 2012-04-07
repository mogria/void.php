<?php

namespace Void;

class JavascriptAsset extends Asset {
  public function __construct($main_file = "application", $folder = "javascripts", $extension = "js") {
    parent::__construct($folder, $extension, $main_file);
  }
}

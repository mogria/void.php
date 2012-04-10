<?php

namespace Void;

class JavascriptAsset extends Asset {
  public function __construct($main_file = "application", $folder = null, $extension = null) {
    if($folder === null) {
      $folder = self::$config->dir;
    }

    if($extension === null) {
      $extension = self::$config->ext;
    }
    parent::__construct($folder, $extension, $main_file);
  }
}

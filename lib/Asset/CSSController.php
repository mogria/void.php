<?php

namespace Void;

class CSSController extends AssetControllerBase {
  public function getAsset($main_file) {
    $asset = new CSSAsset($main_file);
    header('Content-Type: text/css');
    return $asset;
  }
}

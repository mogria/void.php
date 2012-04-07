<?php

namespace Void;

class JSController extends AssetControllerBase {
  public function getAsset($main_file) {
    $asset = new JavascriptAsset($main_file);
    header('Content-Type: text/javascript');
    return $asset;
  }
}

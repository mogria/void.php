<?php

namespace Void;

/**
 * this type of controller simply parses and returns the given Javascript file with
 * the correct HTTP headers
 */
class JSController extends AssetControllerBase {
  public function getAsset($main_file) {
    $asset = new JavascriptAsset($main_file);
    header('Content-Type: text/javascript');
    return $asset;
  }
}

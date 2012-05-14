<?php

namespace Void;

/**
 * this type of controller simply parses and returns the given CSS file with
 * the correct HTTP headers
 */
class CSSController extends AssetControllerBase {
  public function getAsset($main_file) {
    $asset = new CSSAsset($main_file);
    return $asset;
  }

  public function sendHeaders() {
    header('Content-Type: text/css');
  }
}

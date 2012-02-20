<?php

namespace Void;

class FileUtil {
  public static function getExtension($filename) {
    return ($pos = strrpos($filename, ".")) !== false ? substr($filename, $pos + 1) : "";
  }
  
  public static function hasExtension($filename, $extension) {
    ($pos = strrpos($filename, "." . $extension)) !== false && $pos == strlen($filename) - strlen($extension);
  }
  
  public function getMimeType($filename) {
    $info = new finfo(FILEINFO_MIME_TYPE);
    return $info->file($filename);
  }
}
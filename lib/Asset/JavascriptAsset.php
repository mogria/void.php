<?php

namespace Void;

/**
 * This represents a Javascript File (containg directives to include other CSS files)
 *
 */
class JavascriptAsset extends Asset {
  /**
   * Constructor
   *
   * @access public
   * @param string $main_file - the name of the css file
   * @param string $folder    - the name of the folder where the css file is in (if none is set the one in the config is used)
   * @param string $extension - the file extension (if none is set the one in the config is used)
   */
  public function __construct($main_file = "application", $folder = null, $extension = null) {
    // use the configured values if null is given
    if($folder === null) {
      $folder = self::$config->dir;
    }

    if($extension === null) {
      $extension = self::$config->ext;
    }
    // call the parent contstructor
    parent::__construct($folder, $extension, $main_file);
  }
}

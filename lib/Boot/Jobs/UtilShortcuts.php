<?php

namespace Void;

echo "util shortcuts\n";
class UtilShortcuts implements Job {
  /**
   * Some Util Classes which have shortcuts
   * example:
   * s("string") 
   * is the same as
   * new String("string)
   *
   * but the function doesn't get autoloaded
   *
   */
  public function run() {
    new String("");
  }

  public function cleanup() {
  }
}

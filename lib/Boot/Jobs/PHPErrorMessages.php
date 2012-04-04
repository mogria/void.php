<?php

namespace Void;

class PHPErrorMessages extends VoidBase implements Job {

  protected $before_on;
  protected $before_level;

  public static function changeErrorDisplaying($on, $error_level) {
    // change the php ini settings
    ini_set('display_errors', $on); // wheter display errors directly into the webpage or not
    error_reporting($error_level); // the error reporting level
  }

  public function run() {
    // grab the setting wheter errors are on
    $this->before_on = ini_get('display_errors');
    // grab the error reporting as set before
    $this->before_level = error_reporting();


    self::changeErrorDisplaying(self::$config->on, self::$config->level);
  }

  public function cleanup() {
    // change the settings back
    self::changeErrorDisplaying($this->before_on, $this->before_level);
  }
}

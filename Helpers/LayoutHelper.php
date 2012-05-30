<?php

namespace Void;

class LayoutHelper extends ApplicationHelper {
  public function copyright_year() {
    $start_year = 2012;
    $current_year = date("Y");
    return $start_year < $current_year ?  $start_year . " - " . $current_year : $start_year;
  }
}

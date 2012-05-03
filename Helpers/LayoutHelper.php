<?php

namespace Void;

/**
 * Some helper methods for all the templates in the `Views/layout`
 */
class LayoutHelper extends ApplicationHelper {
  public function title($title, $prefix = null) {
    return $prefix ? $prefix . " - " . $title : $title;
  }
}

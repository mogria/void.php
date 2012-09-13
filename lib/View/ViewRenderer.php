<?php

namespace Void;

/**
 * every class that renders a type template of template with a 
 * certain extension should implement this interface
 */
interface ViewRenderer {
  public function getExtension();
  public function getFile();
  public function setFile($file);
  public function setVariables(&$variables);
  public function render();
}

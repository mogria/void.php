<?php

namespace Void;

abstract class AssetControllerBase extends ControllerBase {
  /**
   * Locates the correct asset file
   *
   * @param Dispatcher $dispatcher 
   */
  public function executeAction(Dispatcher $dispatcher) {
    $main_file = $dispatcher->getActionName(null);
    $main_file = implode("/", array_merge(array($main_file), $dispatcher->getParams()));
    $main_file == "index" && $main_file = "application";
    return $this->getAsset($main_file)->load();
  }

  abstract public function getAsset($main_file);

  public function action_index() { }
}

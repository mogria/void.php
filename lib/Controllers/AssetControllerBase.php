<?php

namespace Void;

abstract class AssetControllerBase extends ControllerBase {
  public function executeAction(Dispatcher $dispatcher) {
    $main_file = $dispatcher->getActionName(null);
    $main_file == "index" && $main_file = "application";
    return $this->getAsset($main_file)->load();
  }

  abstract public function getAsset($main_file);

  public function action_index() { }
}

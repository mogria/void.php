<?php

namespace Void;

/**
 * An abstract class for all the controllers which serve assets
 * (javascript, css files etc.)
 *
 * All the classes which inherit this Controller have to implement the getAsset()-Method
 *
 */
abstract class AssetControllerBase extends ControllerBase {
  /**
   * Locates the correct asset file
   *
   * @param Dispatcher $dispatcher 
   */
  public function executeAction(Dispatcher $dispatcher) {
    // concat the action & the params by slashes
    $main_file = $dispatcher->getActionName(null);
    $main_file = implode("/", array_merge(array($main_file), $dispatcher->getParams()));

    // TODO: we should probably not check if it equals "index", (read it from config or) use the Request Class
    // no action specified? we use "application" as file name
    $main_file == "index" && $main_file = "application";
    // output the asset in '$main_file'
    return $this->getAsset($main_file)->load();
  }

  /**
   * returns an asset object of the given file
   *
   * @param string $main_file - the filename
   * @return Asset            - the Asset object of the filename
   */
  abstract public function getAsset($main_file);

  /**
   * We need to implment this function, do nothing in here ... 
   */
  public function action_index() { }
}

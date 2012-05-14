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

    $this->sendHeaders();
    
    if(($modified = Cache::cache_modified(__CLASS__ . $main_file)) !== false) {
      // send 304 not modified if the file is already cached in the browser
      if($this->httpModified(gmdate("r", $modified), $main_file)) {
        // receive the shit from the cache
        $output = Cache::cache(__CLASS__ . $main_file);
      }
    } else {

      // generate the asset in '$main_file'
      $output = $this->getAsset($main_file)->load();

      // cache it
      Cache::cache(__CLASS__ . $main_file, $output);
    }
    // output the asset
    return $output;
  }

  /**
   * returns an asset object of the given file
   *
   * @param string $main_file - the filename
   * @return Asset            - the Asset object of the filename
   */
  abstract public function getAsset($main_file);


  /**
   * Sends the header for the Asset file 
   *
   * For example: 
   * header('Content-Type: text/css');
   * for a css Asset
   *
   * @return void
   */
  abstract public function sendHeaders();

  /**
   * We need to implment this function, do nothing in here ... 
   */
  public function action_index() { }

  /**
   * Sends 304 not modified if the client already received this file
   *
   * @param string $last_modified - the date when the resource under
   *                                $identifier has been last modified
   * @param string $identifer     
   * @return bool
   */
  public function httpModified($last_modified, $identifier) {
    $etag = '"' . md5($last_modified . $identifier) .'"';
    $client_etag = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false;
    $client_last_modified = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? trim($_SERVER['HTTP_IF_MODIFIED_SINCE']) : 0;
    $client_last_modified_timestamp = strtotime($client_last_modified);
    $last_modified_timestamp = strtotime($last_modified);

    if(($client_last_modified && $client_etag) ? (($client_last_modified_timestamp == $last_modified_timestamp) && ($client_etag == $etag)) : (($client_last_modified_timestamp == $last_modified_timestamp) ||($client_etag == $etag))) {
       header('Not Modified', true, 304);
       return false;
    } else {
       header('Last-Modified:' . $last_modified);
       header('ETag:' . $etag);
       return true;
    }
  }
}

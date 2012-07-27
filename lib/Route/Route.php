<?php

namespace Void;

class Route extends VoidBase {

  protected $path_link;

  protected $matches = Array();

  public function __construct($path_link) {
    $this->path_link = $path_link;
  }

  public function match($pattern, $assign) {
    is_array($assign) && $assign = implode("/", $assign);

    $this->matches[$pattern] = $assign;
  }

  public function parse() {
    $matches = Array();
    $result = "";
    foreach($this->matches as $pattern => $assign) {
      $back = (bool)preg_match("/^" . str_replace("/", "\\/", $pattern) . "$/", $this->path_link, $matches);
      if($back) {
        foreach($matches as $key => $match) {
          $assign = str_replace("\\" . $key, $match, $assign);
        }
        $result = $assign;
        break;
      }
    }
    return $result;
  }
}

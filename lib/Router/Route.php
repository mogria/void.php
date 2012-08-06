<?php

namespace Void;
use \BadMethodCallException;

class Route {

  protected $url;
  protected $target;
  protected $names;
  protected $pattern;
  protected $link_url;
  
  const DYNAMIC_URL_PART_REGEX = "\\:([a-zA-Z_][a-zA-Z0-9_]*)";

  public function __construct($url, $target) {
    $this->url = $url;
    $this->target = is_array($target) ? implode("/", $target) : $target;

    $this->compile();
  }
  
  public function getUrl() {
    return $this->url;
  }

  public function getTarget() {
    return $this->target;
  }

  public function getNames() {
    return $this->names;
  }

  public function getPattern() {
    return $this->pattern;
  }

  protected function compile() {
    $this->names = Array();
    $names = &$this->names;
    $this->pattern = "/^" . preg_replace_callback("/\\" . self::DYNAMIC_URL_PART_REGEX . "/", function($match) use (&$names) {
      $names[] = ":" . $match[1];
      return "([^" . preg_quote("/", "/") . "]+)";
    }, preg_quote($this->url, "/")) . "$/D";
    $this->link_url = preg_replace_callback("/" . self::DYNAMIC_URL_PART_REGEX . "/", function($match) {
      static $i = 0;
      $i++;
      return "?$i?";
    }, $this->url);
  }

  public function request($path_info) {
    $matches = Array();
    $back = (bool) preg_match($this->pattern, $path_info, $matches);
    return $back ? str_replace($this->names, array_slice($matches, 1), $this->target) : false;
  }
  
  public function link() {
    $link = $this->link_url;
    if(func_num_args() == count($this->names))  {
      $args = func_get_args();
      foreach($args as $key => $arg) {
        $key++;
        $link = str_replace("?$key?", $arg, $link);
      }
    } else {
      throw new BadMethodCallException("Invalid number of arguments when linking to '{$this->url}'");
    }
    return $link;
  }

}
<?php

namespace Void;
use \BadMethodCallException;

class Route {

  protected $url;
  protected $target;
  protected $names;
  protected $pattern;
  protected $link_url;
  
  const DYNAMIC_URL_PART_REGEX = "\\:(?:\\[([^\\]]+)\\]|)(\\{[\\,0-9]+\\}|\\+|\\*|\\?|)([a-zA-Z_][a-zA-Z0-9_]*)";


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
    $regexs = Array();
    $this->link_url = preg_replace_callback("/" . self::DYNAMIC_URL_PART_REGEX . "/", function($match) use (&$names, &$regexs) {
      static $i = 0;
      $i++;
      print_r($match);
      $names[] = ":" . $match[3];
      ($match[1] == null || $match[1] === "0") && $match[1] = "^/";
      ($match[2] == null || $match[2] === "0") && $match[2] = "+";
      $regexs[$i] = "([" . str_replace(array("/", "]"), array("\\/", "\\]"), $match[1]) ."]" . $match[2] . ")";
      return "\\?$i\\?"; //@todo: this may cause problems
    }, $this->url);

    $this->pattern = "/^" . preg_quote($this->link_url, '/') . "$/D";
    foreach($regexs as $key => $regex) {
      $this->pattern = str_replace("\\\\\\?$key\\\\\\?", $regex, $this->pattern);
    }
    echo $this->pattern . "\n";
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
        $link = str_replace("\\?$key\\?", $arg, $link);
      }
    } else {
      throw new BadMethodCallException("Invalid number of arguments when linking to '{$this->url}'");
    }
    return $link;
  }

}

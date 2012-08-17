<?php

namespace Void;
use \BadMethodCallException;

class Route {

  protected $url;
  protected $target;
  protected $names;
  protected $pattern;
  protected $link_url;
  protected $delimiters;
  
  const DYNAMIC_URL_PART_REGEX = "\\:(?:\\[([^\\]]+)\\]|)(\\{[\\,0-9]+\\}|\\+|\\*|\\?|)([^a-zA-Z_]|)([a-zA-Z_][a-zA-Z0-9_]*)";


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
    $this->names = $this->delimiters = $regexs = Array();
    $names = &$this->names;
    $delims = &$this->delimiters;
    $this->link_url = preg_replace_callback("/" . self::DYNAMIC_URL_PART_REGEX . "/",
      function($match) use (&$names, &$regexs, &$delims) {
        static $i = 0;
        $i++;
        // save the names (needed for a replace later when request() is called)
        $names[] = ":" . $match[4];

        // define default values for regex componentes
        $delim = ($match[3] == null || $match[3] === "0") ? "/" : $match[3];
        ($match[1] == null || $match[1] === "0") && $match[1] = "^" . $delim;
        ($match[2] == null || $match[2] === "0") && $match[2] = "{1}";

        // save the delimiter if multiple blocks are requested
        if($match[2] != "{1}" && $match[2] != "?" && $match[2] != "{0,1}" && $match[2] != "{,1}" && $match[2] != "{0}") {
          $delims[] = $delim;
        } else {
          $delims[] = null;
        }
        // compose regex
        $regexs[$i] = "((?:[" . str_replace(array("/", "]"), array("\\/", "\\]"), $match[1])
          . "]+" . preg_quote($delim, "/") . "?)" . $match[2] . ")";

        // return a string with the index of the match in the middle
        // we don't return the generated regex yet because of problems
        // with preg_quote
        return "\\?$i\\?"; //@todo: this may cause problems, maybe select a better string?
      }, $this->url);

    $this->pattern = "/^" . preg_quote($this->link_url, '/') . "$/D";
    foreach($regexs as $key => $regex) {
      $this->pattern = str_replace("\\\\\\?$key\\\\\\?", $regex, $this->pattern);
    }
  }

  public function request($path_info) {
    $matches = Array();
    // see if the url requested matches the generated pattern
    $back = (bool) preg_match($this->pattern, $path_info, $matches);
    if($back) {
      // if yes replace the specified delimiters by /
      foreach($this->delimiters as $key => $delim) {
        if($delim !== null) {
          $matches[$key + 1] = str_replace($delim, "/", $matches[$key + 1]);
        }
      }
      // replace the placeholders in this->target with the stuff in the url requested
      $back = str_replace($this->names, array_slice($matches, 1), $this->target);
    }
    return $back;
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

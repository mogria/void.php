<?php

namespace Void;
use \BadMethodCallException;

class Route {

  /* simplyfied pattern which the request needs to match */
  protected $url;

  /* target controller/action/params */
  protected $target;

  /* name of the variables in $url */
  protected $names;

  /* auto generated regex pattern (out of url) to match requests */
  protected $pattern;

  /* url with simple placeholders, easy to create links out of it */
  protected $link_url;

  protected $delimiters;

  protected $optional = 0;

  protected $max_args = 0;
  
  /* regex to match variables insude of $url */
  const DYNAMIC_URL_PART_REGEX = "([\\W_]?)\\:(?:\\[([^\\]]+)\\]|)(\\{[\\,0-9]+\\}|\\+|\\*|\\?|)([^a-zA-Z_]?)([a-zA-Z_][a-zA-Z0-9_]*)";


  /**
   * Constructor
   *
   * @param string $url
   * @param mixed $target
   */
  public function __construct($url, $target) {
    $this->url = $url;
    $this->target = (is_array($target) ? implode("/", $target) : $target);

    // create a regex pattern out of url
    $this->compile();
  }
  
  /**
   * Getter for $url
   *
   * @return string
   */
  public function getUrl() {
    return $this->url;
  }

  /**
   * Getter for $target
   *
   * @return string
   */
  public function getTarget() {
    return $this->target;
  }

  /**
   * Getter for names
   *
   * @return array
   */
  public function getNames() {
    return $this->names;
  }

  /**
   * Getter for pattern
   *
   * @return string
   */
  public function getPattern() {
    return $this->pattern;
  }

  /**
   * translate the given $url in the Constructor to an regex pattern.
   * all the variable names get extracted. And replaced by a auto-generated
   * regular expression
   *
   * this also generates $link_url with its placeholders in it
   *
   * @return void
   */
  protected function compile() {
    $this->optional = 0;
    $this->max_args = 0;
    $this->names = $this->delimiters = $regexs = Array();
    $names = &$this->names;
    $delims = &$this->delimiters;
    $optional = &$this->optional;
    $max_args = &$this->max_args;

    $optional_chars_before = Array();

    $this->link_url = preg_replace_callback("/" . self::DYNAMIC_URL_PART_REGEX . "/",
      function($match) use (&$names, &$regexs, &$delims, &$optional, &$max_args, &$optional_chars_before) {
        static $i = 0;
        $i++;
        // save the names (needed for a replace later when request() is called)
        $names[] = ":" . $match[5];

        // define default values for regex componentes
        $delim = ($match[4] == null || $match[4] === "0") ? "/" : $match[3];
        ($match[2] == null || $match[2] === "0") && $match[2] = "^" . $delim;
        ($match[3] == null || $match[3] === "0") && $match[3] = "{1}";

        // save the delimiter if multiple blocks are requested
        if($match[3] != "{1}" && $match[3] != "?" && $match[3] != "{0,1}" && $match[3] != "{,1}" && $match[3] != "{0}") {
          $delims[] = $delim;
        } else {
          $delims[] = null;
        }

        // count number of arguments at max can be given to link() later
        $max_args++;

        // count optioal parameters
        if($is_optional = preg_match('/(\?|\*|\{(?:0|,).*\})/', $match[3])) {
          $optional_chars_before[$i] = $match[1];
          $optional++;
        } else {
          $optional = 0;
        }

        // compose regex
        $regexs[$i] = ($is_optional ? preg_quote($match[1], "/") . "?" : "")
          . "((?:[" . str_replace(array("/", "]"), array("\\/", "\\]"), $match[2])
          . "]+" . preg_quote($delim, "/") . "?)" . $match[3] . ")";

        // return a string with the index of the match in the middle
        // we don't return the generated regex yet because of problems
        // with preg_quote
        $back = (!$is_optional ? $match[1] : "");
        $back .= "\\?$i\\?"; //@todo: this may cause problems, maybe select a better string?
        return $back;
      }, $this->url);

    $this->pattern = "/^" . preg_quote($this->link_url, '/') . "$/D";

    foreach($regexs as $key => $regex) {
      $prefix = "";
      // add a prefix if optional char is before placeholder
      if(isset($optional_chars_before[$key])) {
        $prefix = preg_quote($optional_chars_before[$key], "/");
        $prefix = $prefix . "?";
      }

      // replace placeholder with partial regex
      $this->pattern = str_replace("\\\\\\?$key\\\\\\?", $prefix . $regex, $this->pattern);
    }

    foreach($optional_chars_before as $key => $char) {
      $this->link_url = str_replace("\\?$key\\?", "$char\\?$key\\?", $this->link_url);
    }
    
  }

  /**
   * parses the request and checks if it matches $this->pattern. if not false is returned.
   * If the pattern matches a string in the format /controller/action/param1/param2
   * is returned to identify the controller/action which should be called.
   *
   * @return mixed
   */
  public function request($path_info) {
    $matches = Array();
    // see if the url requested matches the generated pattern
    $back = (bool) preg_match($this->pattern, $path_info, $matches);
    if($back) {
      // if yes replace the specified delimiters by /
      foreach($this->delimiters as $key => $delim) {
        if($delim !== null) {
          $matches[$key + 1] = trim(str_replace($delim, '/', $matches[$key + 1]), '/');
        }
      }
      // replace the placeholders in this->target with the stuff in the url requested
      $back = str_replace($this->names, array_slice($matches, 1), $this->target);
    }
    return $back;
  }
  
  /**
   * called when the link function is called for a route.
   * This method takes the stuff in $this->link_url and replaces the placeholders
   * with the given parameters. throws an exception if an wrong number of arguments is
   * given
   *
   * @return string
   */
  public function link() {
    $link = $this->link_url;

    $num_args = func_num_args();
    // correct number of arguments given?
    if($num_args <= $this->max_args && $num_args >= $this->max_args - $this->optional)  {

      // iterate through the given arguments
      $args = func_get_args();

      // add some empty entries in where for the not given optional arguments
      $this->optional > 0 && $args = array_values(array_merge($args, array_fill(0, $this->optional, null)));
      foreach($args as $key => $arg) {
        // as soon as an variable has an delimiters it takes multiple vales
        // so an array needs to be passed
        $delim = isset($this->delimiters[$key]) ? $this->delimiters[$key] : null;
        $key++;

        // replace the placeholder with the given argument
        if($delim === null) {
          $link = str_replace("\\?$key\\?", $arg, $link);
        } else {
          !is_array($arg) && $arg = array($arg);
          $link = str_replace("\\?$key\\?", implode($delim, $arg), $link);
        }
      }
    } else {
      throw new BadMethodCallException("Invalid number of arguments when linking to '{$this->url}'");
    }

    return $link;
  }

}

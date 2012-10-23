<?php

namespace Void;
use \BadMethodCallException;

class Route {

  protected $simple_expression;
  protected $target;

  /**
   * Constructor
   *
   * @param string $url
   * @param mixed $target
   */
  public function __construct($url, $target) {
    $this->target = (is_array($target) ? implode("/", $target) : $target);
  
    $this->simple_expression = new SimpleExpression($url, "/", true);
  }

  public function getPattern() {
    return $this->simple_expression->getPattern();
  }
  
  /**
   * parses the request and checks if it matches $this->pattern. if not false is returned.
   * If the pattern matches a string in the format /controller/action/param1/param2
   * is returned to identify the controller/action which should be called.
   *
   * @return mixed
   */
  public function request($path_info) {
    return $this->simple_expression->replace_by_names($path_info, $this->target);
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
    $num_args = func_num_args();
    $optional = $this->simple_expression->getOptionalPlaceholderCount();
    $max_args = $this->simple_expression->getPlaceholderCount();
    // correct number of arguments given?
    if($num_args <= $max_args && $num_args >= $max_args - $optional)  {

      $args = func_get_args();

      // add some empty entries in where for the not given optional arguments
      $optional > 0 && $args = array_values(array_merge($args, array_fill(0, $optional, null)));

      foreach($args as $key => $arg) {
        if(is_array($arg)) {
          $args[$key] = array_map('urlencode', $arg);
        } else {
          $args[$key] = urlencode($arg);
        }
      }
      $args = array_map('urlencode', $args);

      $link = $this->simple_expression->replace($args, false);
    } else {
      throw new BadMethodCallException("Invalid number of arguments when linking to '{$this->url}'");
    }

    return $link;
  }

}

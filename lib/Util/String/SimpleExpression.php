<?php

namespace Void;

class SimpleExpression {

  const PLACEHOLDER_FINDER_REGEX = "\\:(?:\\[([^\\]]+)\\]|)(\\{[\\,0-9]+\\}|\\+|\\*|\\?|)([^a-zA-Z_]?)([a-zA-Z_][a-zA-Z0-9_]*)";

  protected $default_delimiter;

  protected $expression;

  protected $pattern;

  protected $placeholders;

  protected $replacement_template;

  protected $num_optional_placeholders;

  protected $num_placeholders;

  public function __construct($expression, $default_delimiter = ";", $force_delim_replace = false) {
    $this->default_delimiter = $default_delimiter;
    $this->expression = $expression;
    $this->force_delim_replace = $force_delim_replace;

    $this->compile();
  }

  public function getPlaceholderCount() {
    return $this->num_placeholders;
  }

  public function getPattern() {
    return $this->pattern;
  }

  public function getExpression() {
    return $this->expression;
  }

  public function getOptionalPlaceholderCount() {
    return $this->num_optional_placeholders;
  }

  protected function compile() {
    // (re)intialize properties & variables
    $this->num_optional_placeholders = 0;
    $this->num_placeholders = 0;
    $this->placeholders = Array();

    // make references to pass use() them in closures
    $optional = &$this->num_optional_placeholders;
    $num_placeholders = &$this->num_placeholders;
    $default_delimiter = $this->default_delimiter;
    $placeholders = &$this->placeholders;

    $offset = 0;
    $match_length = 0;
    $subject = $this->expression;

    $this->replacement_template = preg_replace_callback("/" . self::PLACEHOLDER_FINDER_REGEX . "/",
      function($match) use (&$placeholders, $default_delimiter, &$num_placeholders, &$optional, $subject, &$offset, &$match_length) {
        // count number of matches/placeholders
        $offset = strpos($subject, $match[0], $offset);

        // rename/initialize variables for better readability
        $delimiter = empty($match[3]) ? $default_delimiter : $match[3] ;
        $allowed_chars = empty($match[1]) ? "^" . $delimiter : $match[1];
        $quantity = empty($match[2]) ? "{1}" : $match[2];
        $placeholder_name = $match[4];

        $placeholders[$num_placeholders] = $placeholder = new SimpleExpressionPlaceholder($num_placeholders, $offset - $match_length, $placeholder_name, $allowed_chars, $quantity, $delimiter);
      
        if($placeholder->isOptional()) {
          $optional++;
        }

        $match_length += strlen($match[0]);

        $num_placeholders++;
        return "";
      }, $this->expression);

    $this->pattern = "/^" . $this->replacement(array_map(function($placeholder) {
      return $placeholder->getExpression();
    }, $this->placeholders), function($part) {
      return preg_quote($part, '/'); 
    }) . "$/D";
  }

  public function match($subject) {
    $matches = Array();
    $back = preg_match($this->pattern, $subject, $matches);
    array_shift($matches);
    $matches = array_values($matches);
    if($back) {
      foreach($this->placeholders as $placeholder) {
        if($placeholder->hasMultipleBlocks()) {
          $matches[$placeholder->getId()] = array_diff(explode($placeholder->getDelimiter(), $matches[$placeholder->getId()]), array(''));
        }
      }
    }
    return $back ? $matches : false;
  }

  public function replace_by_names($subject, $replace_template) {
    $matches = $this->match($subject);
    $back = false;
    if(is_array($matches)) {
      if(count($matches) > 0) {
        $replace_from = Array();
        $replace_to = Array();

        foreach($this->placeholders as $placeholder) {
          $replace_from[] = ":" . $placeholder->getName();
          $replace_to[] = $this->concatMultipleBlock($matches[$placeholder->getId()], $placeholder);
        }

        $back = str_replace($replace_from, $replace_to, $replace_template);
      } else  {
        $back = $replace_template;
      }
    }
    return $back;
  }

  public function replace(Array $values, $force_delim_replace = null) {
    return $this->replacement($values, null, $force_delim_replace);
  }

  private function replacement(Array $values, $callback = null, $force_delim_replace = null) {
    if(!is_callable($callback)) {
      $callback = function($part) {
        return $part;
      };
    }
    $concat = "";
    $last_offset = strlen($this->replacement_template);
    // we want the highest offset first so the offsets are correct
    $placeholders = array_reverse($this->placeholders);

    foreach($placeholders as $placeholder) {
      $value = isset($values[$placeholder->getName()]) ? $values[$placeholder->getName()] :
        (isset($values[$placeholder->getId()]) ? $values[$placeholder->getId()] : "");
      $offset = $placeholder->getOffset();
      $part = substr($this->replacement_template, $offset, $last_offset - $offset);
      $part = $callback($part);
      
      $value = $this->concatMultipleBlock($value, $placeholder, $force_delim_replace);
      $concat = $value . $part . $concat;

      $last_offset = $offset;
    }
    $concat = $callback(substr($this->replacement_template, 0, $last_offset)) . $concat;
    return $concat;
  }

  private function concatMultipleBlock($value, $placeholder, $force_delim_replace = null) {
    if(is_array($value) && $placeholder->hasMultipleBlocks()) {
      $value = implode(($force_delim_replace !== null ? $force_delim_replace : $this->force_delim_replace) ? $this->default_delimiter : $placeholder->getDelimiter(), $value);
    }
    return $value;
  }
}

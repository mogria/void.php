<?php

namespace Void;

class SimpleExpression {

  const PLACEHOLDER_FINDER_REGEX = "\\:(?:\\[([^\\]]+)\\]|)(\\{[\\,0-9]+\\}|\\+|\\*|\\?|)([^a-zA-Z_]?)([a-zA-Z_][a-zA-Z0-9_]*)";

  protected $default_delimiter;

  protected $expression;

  protected $placeholders;

  protected $replacement_template;

  protected $num_optional_placeholders;

  protected $num_placeholders;

  public function __construct($expression, $default_delimiter = ";") {
    $this->default_delimiter = $default_delimiter;
    $this->expression = $expression;

    $this->compile();
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
    preg_match($this->pattern, $subject, $matches);
    return $matches;
  }

  public function replace(Array $values) {
    return $this->replacement($values);
  }

  private function replacement(Array $values, $callback = null) {
    $concat = "";
    $last_offset = strlen($this->replacement_template);
    // we want the highest offset first so the offsets are correct
    $placeholders = array_reverse($this->placeholders);
    foreach($placeholders as $placeholder) {
      $value = isset($values[$placeholder->getName()]) ? $values[$placeholder->getName()] :
        (isset($values[$placeholder->getId()]) ? $values[$placeholder->getId()] : "");
      $offset = $placeholder->getOffset();
      $part = substr($this->replacement_template, $offset, $last_offset - $offset);

      if(is_callable($callback)) {
        $part = $callback($part);
      }

      $concat = $value . $part . $concat;

      $last_offset = $offset;
    }
    $concat = substr($this->replacement_template, 0, $last_offset) . $concat;
    return $concat;
  }
}

<?php

namespace Void;

class SimpleExpression {

  protected $default_delimiter;

  protected $expression;

  protected $replacement_template;

  protected $place_holder_names;

  const PLACEHOLDER_FINDER_REGEX = "([\\W_]?)\\:(?:\\[([^\\]]+)\\]|)(\\{[\\,0-9]+\\}|\\+|\\*|\\?|)([^a-zA-Z_]?)([a-zA-Z_][a-zA-Z0-9_]*)";

  protected $delimiters;

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
    $this->placeholder_names = Array();
    $this->delimiters = Array();
    $placeholder_expressions = Array();

    // make references to pass use() them in closures
    $names = &$this->placeholder_names;
    $delimiters = &$this->delimiters;
    $optional = &$this->num_optional_placeholders;
    $num_placeholders = &$this->num_placeholders;
    $default_delimiter = $this->default_delimiter;


    $optional_chars_before_placeholder = Array();

    $this->replacement_template = preg_replace_callback("/" . self::PLACEHOLDER_FINDER_REGEX . "/",
      function($match) use (&$names, &$placeholder_expressions, &$delimiters, $default_delimiter, &$num_placeholders, &$optional_chars_before_placeholder) {
        // count number of matches/placeholders
        $num_placeholders++;

        // rename/initialize variables for better readability
        $char_before = $match[1];
        $delimiter = empty($match[4]) ? $default_delimiter : $match[4] ;
        $allowed_chars = empty($match[2]) ? "^" . $delimiter : $match[2];
        $quantity = empty($match[3]) ? "{1}" : $match[3];
        $placeholder_name = $match[5];

        // save the names (needed for a replace())
        $names[] = ":" . $placeholder_name;
      }, $this->expression);

  }

  protected function compilePlaceholder() {
  }

  public function match() {
  }

  public function replace() {
  }
}

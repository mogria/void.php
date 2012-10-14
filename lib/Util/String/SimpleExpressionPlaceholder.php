<?php

namespace Void;

class SimpleExpressionPlaceholder {

  // delimited used for regular expressions which use the getExpression() method
  // this is needed to quote the expression returned properly
  const REGEX_DELIMITER = '/';

  protected $name;

  public function getName() {
    return $this->name;
  }

  protected $id;

  public function getId() {
    return $this->id;
  }

  protected $optional;

  public function getOptional() {
    return $this->optional;
  }

  protected $numerically;

  public function getNumerically() {
    return $this->numerically;
  }

  protected $delimiter;

  public function getDelimiter() {
    return $this->delimiter;
  }

  protected $char_range;

  public function getCharRange() {
    return $this->char_range;
  }

  protected $offset;

  public function getOffset() {
    return $this->offset;
  }

  protected $is_optional;

  protected $multiple_blocks;

  public function __construct($id, $offset, $name, $char_range, $numerically, $delimiter) {
    $this->id = $id;
    $this->offset = $offset;
    $this->name = $name;
    $this->char_range = $char_range;
    $this->numerically = $numerically;
    $this->multiple_blocks = $this->checkMultipleBlocks($numerically);
    $this->delimiter = ($this->multiple_blocks) ? $delimiter : null;
    $this->is_optional = preg_match('/(\?|\*|\{(?:0|,).*\})/', $numerically);
  }

  private function checkMultipleBlocks($numerically) {
    return $numerically != "{1}" && $numerically != "?" && $numerically != "{0,1}" && $numerically != "{,1}" && $numerically != "{0}";
  }

  public function isOptional() {
    return $this->is_optional;
  }

  public function hasMultipleBlocks() {
    return $this->multiple_blocks;
  }

  public function getExpression() {
    return "((?:[" . str_replace(array(self::REGEX_DELIMITER, ']'), array("\\" . self::REGEX_DELIMITER, "\\]"), $this->char_range)
      . "]+" . preg_quote($this->delimiter, self::REGEX_DELIMITER) . "?)" . $this->numerically . ")";
  }
}

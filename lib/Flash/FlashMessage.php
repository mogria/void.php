<?php

namespace Void;

/**
 * An Simple object containing a tyoe & a message
 *
 * This is used by the Flash Class
 *
 */
class FlashMessage extends VoidBase {
  
  /**
   * @var string
   */
  protected $message;

  /**
   * @var string
   */
  protected $type;

  /**
   * setter for message
   */
  public function setMessage($new_message) {
    $this->message = $new_message;
  }

  /**
   * getter for message
   */
  public function getMessage() {
    return $this->message;
  }

  /**
   * setter for type
   */
  public function setType($new_type) {
    $this->type = $new_type;
  }

  /**
   * getter for type
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Constructor
   *
   * @param string $type
   * @param string $message
   */
  public function __construct($type, $message) {
    $this->setType($type);
    $this->setMessage($message);
  }

  /**
   * return type & message in the following format
   * %s: %s\n
   */
  public function __toString() {
    return $this->type . ": " . $this->message . "\n";
  }
}

<?php

namespace Void;

class FlashMessage extends VoidBase {
  
  protected $message;

  protected $type;

  public function setMessage($new_message) {
    $this->message = $new_message;
  }

  public function getMessage() {
    return $this->message;
  }

  public function setType($new_type) {
    $this->type = $new_type;
  }

  public function getType() {
    return $this->type;
  }

  public function __construct($type, $message) {
    $this->setType($type);
    $this->setMessage($message);
  }

  public function __toString() {
    echo $this->type . ": " . $this->message;
  }
}

<?php

namespace Void\HTML;

use \ActiveRecord\Model;

class LabelTag extends Tag {
  public function __construct($name, $for, $attributes = Array()) {
    parent::__construct("label", null, $attributes);
    if($name instanceof Model) {
      $this->for = $for;
      // call the proper method on the model to get the Columnname
      $this->setContent($for);
    } else {
      $this->setContent($name);
      $this->for = $for;
    }
  }
}

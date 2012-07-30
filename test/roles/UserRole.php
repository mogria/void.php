<?php

namespace Void;

class UserRole extends UnregistredRole {
  protected static $id = 15;

  static $view = true;
  static $edit = true;
  static $vote = true;
  static $something = false;
}

<?php

namespace Void;

interface Authentification {

  public static function auth_init($force);
  public function login();
  public function logout();
  public function get_role();
  public function reload();
}

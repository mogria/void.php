<?php

namespace Void;

interface Authentification {

  public function login();
  public function logout();
  public function get_role();
  public function reload();
}

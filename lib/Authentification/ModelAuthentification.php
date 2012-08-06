<?php

namespace Void;
use \ActiveRecord\Model;

/**
 * base class for all model which can get authentificated
 *
 * This only partially implements the interface Authentification. It implements
 * the `reload()` and the `logout()` method. You still need to implement:
 * - auth_init()
 * - login()
 * - get_role()
 */
abstract class ModelAuthentification extends Model implements Authentification {
  public function logout() {
    static::auth_init(true);
  }
}

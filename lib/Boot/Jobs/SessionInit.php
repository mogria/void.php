<?php

namespace Void;

class SessionInit extends VoidBase implements Job {
  /**
   * initializes the
   * session & enables regeneration of the session id (improves security) 
   */
  public function run() {
    session_start();
    session_regenerate_id(true);
    
    $models = self::$config->models;
    if(is_array($models)) {
      foreach($models as $model) {
        $model = "\\Void\\" . $model;
        $model::auth_init(false);
      }
    }
  }

  public function cleanup() {
  }
}

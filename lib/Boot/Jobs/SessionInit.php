<?php

namespace Void;

class SessionInit implements Job {
  /**
   * initializes the
   * session & enables regeneration of the session id (improves security) 
   */
  public function run() {
    session_start();
    session_regenerate_id(true);
  }

  public function cleanup() {
  }
}

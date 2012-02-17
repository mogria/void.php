<?php

namespace Void;

/**
 * This class converts normal PHP-Errors (inclusive Notice's and stuff) to exceptions
 */
class ErrorToException implements Job {
  
  /**
   * adds the exception_error_handler()-Method to the error_handlers
   */
  public function run() {
    set_error_handler(Array(__CLASS__, "exception_error_handler"));
  }
  
  /**
   * Cleanup? Nothing to do here
   *
   *      ############
   *     ##          ####
   *   ##  **     **  ####
   *   ##                ##
   *   ##  ##########    ####
   * ####                ####
   *   ##                ####
   *   ##                ##
   *   ####            ####
   *     ####        #### 
   *       ##88####88``######
   *       88########8888`
   *     ####  ##8888``88##
   *             ##    ########
   *               ##  88888888##
   *     ########``88````##888888
   *   ##88####8888####    ##8888 
   *   88##    ##88          ######
   *   88##      88##
   *   ##88        8888
   *     ##88
   *       ##
   */
  public function cleanup() {}

  /**
   * This function which will be called if an PHP error occurs,
   */
  public static function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
  }

}

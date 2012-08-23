<?php

namespace Void;

class PagesController extends ApplicationController {
  public function action_index() { }
  public function action_about() {
    $this->title = "About";
  }
  public function action_get_involved() {
    $this->title = "Get Involved";
  }
  public function action_help() {
    $this->title = "Help";
  }

  public function action_test_forms() {
    $this->title = "Just testing the Form helper";
  }

  public function action_params() {
    echo 'pages/params/' . implode("/", func_get_args());
    exit;
  }

  public function action_math($operator) {
    $operators = Array('+', '-', '*', ':');
    $this->result = null;
    $this->calc = "fehlerhafte rechnung";
    if(!in_array($operator, $operators)) {
      $this->calc = "no such operator";
    } else {
      $numbers = array_slice(func_get_args(), 1);
      if($numbers != null) {
        $numbers = array_map(function($v) {
          return (double)$v;
        }, $numbers);
        $this->calc = implode(" $operator ", $numbers);
        $this->calc = str_replace(':', '/', $this->calc);
        try {
          $this->result = eval("return " . $this->calc . ";");
        } catch(\Exception $ex) { }
      }
    }
  }
}

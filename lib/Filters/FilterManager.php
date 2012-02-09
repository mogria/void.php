<?php

namespace Void;

class FilterManager implements Filter {
  protected $filters;
  
  public function add(Filter $job) {
    $this->filters[] = $job;
  }
  
  public function remove(Filter $job) {
    array_diff($this->filters, array($job));
  }
  
  public function filter(Array $list) {
    $newlist = array();
    foreach($this->filters as $filter) {
      foreach($list as $key => $value) {
        if($filter->filter($value)) {
          $newlist[$key] = $value;
        }
      }
    }
    return $newlist;
  }
}
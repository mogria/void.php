<?php

namespace Void;

class JobCollection implements Job {
  
  protected $jobs = Array();
  
  public function add(Job $job) {
    $this->jobs[] = $job;
  }
  
  public function remove(Job $job) {
    array_diff($this->jobs, array($job));
  }
  
  public function run() {
    foreach($this->jobs as $job) {
      $job->run();
    }
  }
  
  public function cleanup() {
    foreach($this->jobs as $job) {
      $job->cleanup();
    }
  }
}
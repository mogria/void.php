<?php

namespace Void;

interface Job {
  public function run();
  public function cleanup();
}
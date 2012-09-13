<?php

namespace Void;

/**
 * all ViewRenderer which need access to an Helper Class should 
 * implement this interface
 */
interface HelpableRenderer extends ViewRenderer {
  public function setHelper(HelperBase $helper);
  public function getHelper();
}

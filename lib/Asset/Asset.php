<?php

namespace Void;

class Asset extends VoidBase {

  protected $extension;
  protected $directory;
  protected $main_file;

  public function __construct($directory, $extension, $main_file = "application") {
    $this->directory = $directory;
    $this->extension = $extension;
    $this->main_file = $main_file;
  }

  public function load() {
    $directives = Array(
      'require_file',
      'require_dir',
      'require_recursive'
    );
    $content = file_get_contents($this->directory. DS . $this->main_file . "." . $this->extension);
    $matches = Array();
    $self = $this;

    foreach($directives as $directive) {
      $content = preg_replace_callback(
        '/^#=[ ]*' . preg_quote($directive) . '[ ]+([^\\n$]*)/i',
        function($matches) use ($directive, $self) {
          $method = "handler_" . $directive;
          return $self->$method($matches[1]);
        },
        $content
      );
    }

    return $content;
  }

  public function handler_require_file($file) {
    $str = "";

    $cwd = getcwd();  // get the current directory
    chdir($this->directory); // change to the assets directory
    $files = glob($file); // returns all the files that matched the pattern
    chdir($cwd); // change back to the original directory
    foreach($files as $file) {
      $str .= $this->require_single_file($file);
    }

    return $str;
  }

  public function require_single_file($file) {
    if(!is_file($this->directory . DS . $file)) {
      throw new \BadMethodCallException("Asset file '{$this->directory}" . DS . "$file' does not exist!");
    }
    return "/** $file **/\n" .
      file_get_contents($this->directory . DS . $file)
      . "\n";
  }

  public function handler_require_dir($dir) {
    $str = "";
    $cwd = getcwd();
    chdir($this->directory);
    $files = array_diff(scandir($dir), array(".", ".."));
    foreach($files as $file) {
      $str .= $this->require_single_file($file);
    }
    chdir($cwd);
    return $str;
    
  }

  public function handler_require_recursive($dir) {
    $str = "";
    $cwd = getcwd();
    chdir($this->directory);
    $iterator = RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach($iterator as $file) {
      $str .= require_single_file($file . "");
    }
    chdir($cwd);
    return $str;
  }
}

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
    // the three possible commands you can use in a Asset file
    $directives = Array(
      'require',
      'require_dir',
      'require_tree'
    );

    $content = file_get_contents($this->directory. DS . $this->main_file . "." . $this->extension);
    $matches = Array();
    $self = $this;

    foreach($directives as $directive) {
      $content = preg_replace_callback(
        '/^\/\/=[ ]*' . preg_quote($directive) . '[ ]+([^\\n$]*)/mi',
        function($matches) use ($directive, $self) {
          $method = "handler_" . $directive;
          return $self->$method($matches[1]);
        },
        $content
      );
    }

    return $content;
  }

  public function handler_require($file) {
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
    chdir($cwd);

    foreach($files as $file) {
      $str .= $this->require_single_file($dir . DS . $file);
    }

    return $str;
    
  }

  public function handler_require_tree($dir) {
    $str = "";
    $cwd = getcwd();
    chdir($this->directory);
    $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
    chdir($cwd);
    foreach($iterator as $file) {
      if(is_file($this->directory . DS . $file)) {
        $str .= $this->require_single_file($file);
      }
      echo "\n";
    }
    return $str;
  }
}

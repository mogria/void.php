<?php

namespace Void;

/**
 * Represents an Asset (a .css or a .js file or similiar which is not directly in the html
 * Inside this file you can use certain statements like in the following example:
 *
 * -- application.css --
 * //= require style.css
 * //= require_tree modules
 * 
 *
 * if the directory structure is something like this
 *
 * stylesheets/
 *  |- modules/
 *  |   |- menu.css
 *  |   `- footer.css
 *  |- application.css
 *  |- randomcssfile.css
 *  `- style.css
 *
 * the following output will be generated:
 * /** style.css ** /
 *
 * .some-selector {
 *   font-size: 15px;
 * }
 *
 *
 * /** modules/menu.css ** /
 * .menu {
 *   list-style-type: none;
 * }
 *
 * /** modules/footer.css ** /
 * footer {
 *   font-size: 12px;
 * }
 *
 */
class Asset extends VoidBase {

  /**
   * @var string $extension - the extension of the asset file
   * @access protected
   */
  protected $extension;

  /**
   * @var string $directory - the directory where these asset files are in
   * @access protected
   */
  protected $directory;

  /**
   * @var string $main_file - the file which contains "require" statements
   * @access protected
   */
  protected $main_file;

  /**
   * the commands you can use in a Asset file
   * @var Array $directives
   * @access public
   */
  static protected $directives = Array(
    'require',
    'require_dir',
    'require_tree',
    'require_self',
  );

  /**
   * Simple Constructor.
   * Initializes the three protected properties $extension, $directory and $main_file
   *
   * @param string $directory
   * @param string $extension
   * @param string $main_file
   * @access public
   */
  public function __construct($directory, $extension, $main_file = "application") {
    $this->directory = $directory;
    $this->extension = $extension;
    $this->main_file = $main_file;
  }

  /**
   * Loads the given $main_file executes the commands in it
   *
   * @access public
   * @return string - the $main_file & the composed content
   */
  public function load() {
    $content = "";
    foreach($this->getFileList() as $file) {
      $content .= $this->require_single_file($file);
    }
    return $content;
  }

  /**
   * returns a list of files which are included by the main_file
   *
   * @access public
   * @return Array 
   */
  public function getFileList() {
    // read the main file
    $content = file_get_contents($this->directory. DS . $this->main_file . "." . $this->extension);
    // search for all the three commands inside of the file
    // we are using a regular expression to do this
    $ok = preg_match_all(
      '/^\/\/=[^\S\\n]*(' . implode("|", array_map('preg_quote', self::$directives)) . ')[^\S\\n]+([^\\n$]*)/mi',
      $content,
      $matches, PREG_SET_ORDER);

    $list = Array();
    if($ok) {
      // iterate all the results and build an array of all the files
      foreach($matches as $match) {
        $method = "handler_" . $match[1];
        $list = array_merge($list, $this->$method(trim($match[2])));
      }
    } else {
      $list = array_merge($list, $this->handler_require_self());
    }
    return $list;
  }

  /**
   * This reads a single file and puts the filename in comments at the beginning of the file
   *
   * Throws an BadMethodCallException if the file doesn't exist.
   *
   * @access public
   * @param string $file - the filename
   * @return string - the file read with the filename on top
   */
  public function require_single_file($file) {
    if(!is_file($this->directory . DS . $file)) {
      throw new \BadMethodCallException("Asset file '{$this->directory}" . DS . "$file' does not exist!");
    }
    return "/** $file **/\n" .
      file_get_contents($this->directory . DS . $file)
      . "\n";
  }

  /**
   * This method gets called if a single file is included from the main file
   *
   * @access public
   * @param string $file
   * @return Array - list of files
   */
  public function handler_require($file) {
    $str = "";

    $cwd = getcwd();  // get the current directory
    chdir($this->directory); // change to the assets directory
    $files = glob($file); // returns all the files that matched the pattern
    chdir($cwd); // change back to the original directory

    return $files;
  }

  /**
   * This method gets called if the main_file includes itself by calling require_self
   *
   * @access public
   * @return Array - list of files
   */
  public function handler_require_self() {
    return $this->handler_require($this->main_file . "." . $this->extension);
  }

  /**
   * This includes all the files from a directory (but not the files in the subdirectories of the directory)
   *
   * @access public
   * @param string $dir - the directory
   * @return Array - list of files
   */
  public function handler_require_dir($dir) {
    return $this->handler_require_tree($dir, 1);
  }

  /**
   * This includes all the files in the given directory (and subdirectories) until the $depth (-1 => unlimited) is reached
   *
   * @access public
   * @param $dir - the directory
   * @param $depth
   * @return Array - list of files
   */
  public function handler_require_tree($dir, $depth = -1) {
    $list = Array();

    // scan the given directory for files
    $cwd = getcwd();
    chdir($this->directory);
    if(!is_dir($dir)) {
      throw new \BadMethodCallException("The directory '$this->directory/$dir' does not exist!");
    }
    $files = array_diff(scandir($dir), array(".", ".."));
    chdir($cwd);

    // iterate through each file
    foreach($files as $file) {
      $filename = $dir . DS . $file;
      // is it a file or a directory?
      if(is_file($this->directory . DS . $filename)) {
        // append it to the list if it is a file
        $list[] = $filename;
      } else if(is_dir($this->directory . DS . $filename)) {
        // do a recursive call if it is a directory until the $depth limit is reached
        if($depth == -1 || (--$depth > 0)) {
          $list = array_merge($list, $this->handler_require_tree($filename, $depth));
        }
      }
    }

    return $list;
  }

  public function getDirectory() {
    return $this->directory;
  }
}



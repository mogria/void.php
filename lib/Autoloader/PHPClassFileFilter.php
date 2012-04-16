<?php

namespace Void;

use FilterIterator;

/**
 * a simple iterator which filters .php files which are probably containing classes. 
 *
 */
class PHPClassFileFilter extends FilterIterator {
  /**
   * the directories in which will not be searched 
   * @todo: move this to Autoloader.php or even default_configuration.php ?
   * @var Array $excluded_dirs
   * @access protected
   */
  protected $excluded_dirs = Array(
    "lib/Model"
  );


  /**
   * if this function returns false, the current element is filtered out
   *
   * @todo: define extension in default_configuration.php ?
   *
   * @access public
   * @return bool
   */
  public function accept() {
    $ext = ".php";
    $value = $this->getInnerIterator()->current();
    $file = $value->getFilename();
    return $value->isFile()
      && ucfirst($file) == $file
      && ($position = strrpos($file, $ext)) !== false
      && strlen($file) - strlen($ext) == $position
      && !$this->inExcludedDir($value->getPath());
  }

  /**
   * checks if the given file/directory is in one the excluded dirs
   *
   * @access public
   * @param string $file
   * @return bool
   */
  public function inExcludedDir($file) {
    $inside = false;
    $file_before = $file;
    $file = null;
    // iterate through each excluded directory
    foreach($this->excluded_dirs as $dir) {
      // replace all normal slashes in the path with the
      // directory separator (for windows compatibility)
      $dir = str_replace("/", DS, $dir);
      // go all directories back and check if the
      // directories are matching with the current excluded directory
      while($file != $file_before && $file != DS && $file != "." && !$inside) {
        $file = $file_before;
        // echo str_pad($file, 80, " ", STR_PAD_RIGHT) . ": "
        //   . str_pad(substr($file, -strlen($dir)), strlen($dir), " ", STR_PAD_RIGHT)
        //   . " === " . $dir . "\n";
        if(substr($file, -strlen($dir)) === $dir) {
          $inside = true;
        }
        $file_before = dirname($file);
      }
    }
    return $inside;
  }
}

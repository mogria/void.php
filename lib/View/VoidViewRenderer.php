<?php

namespace Void;

class VoidViewRenderer extends PHPViewRenderer {
  /**
   * return extension of files this renderer is responsible for
   *
   */
  public function getExtension() {
    return "tpl";
  }

  /**
   * render a .tpl file
   *
   * @return string - rendered content
   */
  public function render() {
    if(($result = $this->handleRenderCallAsHelperMethod(func_get_args())) !== null) {
      return $result;
    }
    $this->executable_content = $parsed_file = $this->parse(file_get_contents($this->file));
    return parent::render();
  }

  /**
   * Convert the Template Synatx in the Template files to PHP
   * within these brackets {} you can simply use PHP Code
   * if you use {= } the text will be echo'ed out.
   * if you use {> } the text will be echo'ed out safely.
   * if you use {[ } an other template object is rendered in place
   * @param string $string
   * @return string
   */
  public function parse($string) {
    // match every { } pair and replace it with the correct php code
    return preg_replace_callback(
      "/\\{(\\[|>|=|)\s*([^\\}]*?({(?:.*|(?3))}|)[^\\{]*)\\}($)?/m",
      function($match) {
        $before = '<' . '?php ';
        $after = ' ?' . '>';
        switch($match[1]) {
          case '>':
            $before .= "echo \$this->h(";
            $after = ")" . $after;
            break;
          case '=':
            $before .= "echo ";
            break;
          case '[':
            $before .= "echo ";
            $after = '->render()' . $after;
            break;
          case '':
            break;
          default:
            return $match[0];
            break;
        }

        // replace : with $this-> (but not in static calls)
        $match[2] = preg_replace("/(?:[^\:]|^)\:([a-z_][a-z0-9_]*\\()/i",
          "\$this->\\1",
          $match[2]);

        // replace strings like h"random strings" with a call to $this->h()
        $match[2] = preg_replace("/h((\"|')(?:\\2|.*?[^\\\\](?:\\\\\\\\)*\\2))/",
          "\$this->h(\\1)",
          $match[2]);

        if(isset($match[3])) {
          $after .= "\n";
        }

        return $before . $match[2] . $after;
      }, $string);
  }
}

<?php
/**
 * @author Mogria
 */

namespace Void;

use BadMethodCallException;

/**
 * A Template System
 */
class Template extends VirtualAttribute {
  /**
   * @var string $file
   */
  protected $file;

  /**
   * Constructor
   * @param string $file
   * @param Array $initializers
   */
  public function __construct($file, Array $initializers = Array()) {
    $this->setFile($file);
    $this->_ = $initializers;
  }

  /**
   * this sets the template file which should be used.
   * You can also pass an Array, see the TemplateFinder class
   *
   * @param mixed $file
   * @see TemplateFinder
   */
  public function setFile($file) {
    $finder = new TemplateFinder($file);
    $this->file = $finder->getPath();
  }

  /**
   * returns the path to the used template file
   *
   * @return string
   */
  public function getFile() {
    return $this->file;
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
    return preg_replace_callback(
      "/\\{(\\[|>|=|)\s*(:|)(.*)\\}($)?/m",
      function($match) {
        $before = '<' . '?php ';
        $after = ' ?' . '>';
        switch($match[1]) {
          case '>':
            $before .= "print(htmlspecialchars(";
            $after = ", ENT_QUOTES, 'UTF-8'))" . $after;
            break;
          case '=':
            $before .= "print(";
            $after = ")" . $after;
            break;
          case '[':
            $before .= "print(";
            $after = '->render())' . $after;
            break;
          case '':
            break;
          default:
            return $match[0];
            break;
        }

        if($match[2] === ':') {
          $before .= '$this->';
        }
        
        if(isset($match[4])) {
          $after .= "\n";
        }
        return $before . $match[3] . $after;
      }, $string);
  }

  /**
   * the template file set will be rendered. All the
   * variables set (by using the method's from virtual attribute)
   * are available in the Template file.
   *
   * if you don't pass any parameters the properties of the object will be used,
   * if you pass some parameters only the values in $initializers are used to render
   * the given template file ($filespec)
   *
   * @param mixed $filespec the file which should be rendered
   * @param Array $initializers the variables which can be used within the Template
   * @return string the output of the template
   */
  public function render($filespec = null, $initializers = Array()) {
    if($filespec === null) {
      extract($this->toArray());
      ob_start();
      eval( <<<_VOID_TEMPLATE
namespace Void; ?>{$this->parse(file_get_contents($this->file))}
_VOID_TEMPLATE
      );
      $content = ob_get_contents();
      ob_end_clean();
      return $content;
    } else {
      $template = new Template($filespec, $initializers);
      return  $template->render();
    }
  }
  
  /**
   * Link to a certain Controller
   *
   * @param type $controller
   * @param type $action
   * @param array $params
   * @return string
   * @see Router 
   */
  public function link($controller = null, $action = null, Array $params = Array()) {
    return Router::link($controller, $action, $params);
  }

  /**
   * Gets called if you call a method on this object which doesn't exists.
   * 
   * - if you call a Method on this ending with "Tag"
   *   An HTML tag will be returned with the name of the caled method (without "Tag" @ the and)
   *   The first Parameter is the content, the second an array of attributes with key => value pairs
   *   
   *   - if a class exists with the name of the Called Method (like ATag), this class will be used to
   *     create the HTML Tag and all the arguments given will be passed to the __construct Method of this class
   *   
   * 
   * 
   * @param type $method
   * @param type $args
   * @return Tag
   * @throws BadMethodCallException 
   */
  public function __call($method, $args) {
    // Check if the called function ends with "Tag"
    if (substr($method, -strlen("Tag")) === "Tag") {
      // generate the class name
      $tagname = ucfirst(strtolower(substr($method, 0, -strlen("Tag"))));
      // check if the class exists
      if(class_exists($classname = __NAMESPACE__ . "\\" . $tagname . "Tag")) {
        // create an instance and call the constructor
        $tag = new $classname();
        // pass all the arguments to the contructor
        call_user_func_array(Array($tag, '__construct'), $args);
      } else {
        // create a instance of the common Tag class
        $tag = new Tag($tagname, array_shift($args), ($params = array_shift($args)) === null ? Array() : $params);
      }
      // return the Tag Object
      return $tag;
    } else {
      // Nothing to do here 
      throw new BadMethodCallException("There is no magic method '$method'.");
    }
  }
}

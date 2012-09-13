<?php

namespace Void;
use \BadMethodCallException;

class PHPViewRenderer extends VirtualAttribute implements HelpableRenderer {

  protected $file;

  protected $executable_content = null;

  protected $variables;

  protected $helper = null;

  // overwrite constructor of virtual attribute
  public function __construct() { }

  /**
   * get the extension of the files this renderer is responsible
   */
  public function getExtension() {
    return "php";
  }

  /**
   * get the file which gets rendered
   *
   * @return string
   */
  public function getFile() {
    return $this->file;
  }

  /**
   * set the file which gets rendered
   *
   * @param string $file
   */
  public function setFile($file) {
    $this->executable_content = null;
    $this->file = $file;
  }

  /**
   * set the Helper class which provides Helper functions to
   * use when rendering a template
   */
  public function setHelper(HelperBase $helper) {
    $this->helper = $helper;
  }

  /**
   * get the Helper class assigned to this ViewRenderer
   * @return HelperBase
   */
  public function getHelper() {
    return $this->helper;
  }

  /**
   * store the assigned variables from the controller inside of
   * this renderer to make them available when rendering a template
   * @param $variables - An reference to an array containing all variables
   */
  public function setVariables(&$variables) {
    $this->setReference($variables);
  }

  public function handleRenderCallAsHelperMethod($args) {
    $num_args = count($args);
    if($num_args > 0 && $num_args <= 2) {
      $filespec = $args[0];
      $initializers = $num_args > 1 ? $args[1] : array();
      $template = new Template($filespec, $initializers);
      return  $template->render();
    }
    return null;
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
  public function render() {
    if(($result = $this->handleRenderCallAsHelperMethod(func_get_args())) !== null) {
      return $result;
    }
    unset($result);
    if($this->executable_content === null) {
      $this->executable_content = file_get_contents($this->getFile());
    }
    extract($this->toArray());
    try {
      ob_start();
      $back = eval(<<<_VOID_TEMPLATE
namespace Void; ?>{$this->executable_content}
_VOID_TEMPLATE
    );
      $content = ob_get_contents();
      ob_end_clean();
    } catch (Exception $ex) {
      $content = null;
      self::$config->onDevelopment() && $content = $this->showDebugInformation();
      echo $ex;
    }

    return $content;
  }

  /**
   * print out the rendered .tpl file for debuging
   *
   */
  public function getDebugInformation() {
    // replace all line breaks with unix line breaks
    $file = str_replace(Array("\r\n", "\r"), "\n", $this->executable_content);
    $file = explode("\n", $file);
    $lines = count($file);
    // add the line number to each line
    $file = array_map(function(&$current_line) use ($lines) {
      static $linenr = 0;
      $linenr++;
      return str_pad($linenr, strlen((string)$lines), " ", STR_PAD_LEFT) . " | " . $current_line;
    }, $file);
    // open <pre> tag and add some space
    $content  = "<pre>" . str_repeat(" ", strlen((string)$lines) + 3);
    // output the file name (in bold)
    $content .= "<b>" . $this->getFile() . "</b> <i>(parsed)</i>\n";
    // output the file & the line numbers
    $content .= htmlspecialchars(implode("\n", $file), ENT_QUOTES, 'UTF-8');
    // close pre tag & output the rest
    $content .= "</pre>" . ob_get_contents();
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
    if(method_exists($this, $method) && $method !== '__call' && $reflection = new ReflectionMethod(__CLASS__, $method) && $reflection->isPublic()) {
      call_user_func_array(Array(__CLASS__, $method, $args));
    // Check if the called function ends with "Tag"
    } else if (substr($method, -strlen("Tag")) === "Tag") {
      // generate the class name
      $tagname = ucfirst(strtolower(substr($method, 0, -strlen("Tag"))));
      // check if the class exists
      if(class_exists($classname = __NAMESPACE__ . "\\HTML\\" . $tagname . "Tag")) {
        // create an instance and call the constructor
        $tag = new $classname();
        // pass all the arguments to the contructor
        call_user_func_array(Array($tag, '__construct'), $args);
      } else {
        // create a instance of the common Tag class
        $tag = new HTML\Tag($tagname, array_shift($args), ($params = array_shift($args)) === null ? Array() : $params);
      }
      // return the Tag Object
      return $tag;
    // is it a link call?
    } else if(substr($method, 0, 5) === "link_") {
      return call_user_func_array(Array(__NAMESPACE__ . '\\Router', $method), $args);
    // is it a Helper-method
    } else if($this->helper != null && method_exists($this->helper, $method)) {
      // call the methods of the Helper Class
      return call_user_func_array(Array($this->helper, $method), $args);
    } else {
      // Nothing to do here 
      throw new BadMethodCallException("There is no template or helper method '$method'");
    }
  }

}
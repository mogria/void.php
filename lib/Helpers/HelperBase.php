<?php

namespace Void;
use \BadMethodCallException;

/**
 * All the Helper classes extend this class
 * All method calls will be forwarded to the template object
 */
class HelperBase extends VirtualAttribute {

  /**
   * @var ViewRenderer
   * @access protected
   */
  protected $view_renderer = null;
  
  /**
   * @param Template $template
   * Constructor
   */
  public function __construct(Template $template) {
    $this->setReference($template->getReference());
  }

  /**
   * Forwards all the method calls to the template object
   *
   * @param string $method - the name of the method to call
   * @param Array $args    - the arguments for the method call
   * @return mixed
   */
  public function __call($method, $args) {
    if($this->view_renderer != null) {
      return $this->view_renderer->__call($method, $args);
    } else {
      throw new BadMethodCallException('no template renderer set to forward function calls');
    }
  }

  /**
   * sets the view renderer.
   * All the function calls get forwarded to this objects
   *
   * @param ViewRenderer $view_renderer
   */
  public function setViewRenderer(ViewRenderer $view_renderer) {
    $this->view_renderer = $view_renderer;
  }

  /**
   * reads css files included in $main_file and returns link tags to all of them
   * if no file is specified, a css-link-tag gets created for the file $main_file
   *
   * @param string $main_file - the css file
   * @param Array $attributes - additional attibutes to the link tag(s)
   * @return string - the link tag(s)
   */
  public function stylesheet($main_file, Array $attributes = Array()) {
    return $this->assetLinks("link", "CSSAsset", $main_file, $attributes);
  }

  /**
   * reads javascript files included in $main_file and returns link tags to all of them
   * if no file is specified, a script-tag gets created for the file $main_file
   *
   * @param string $main_file - the javascript file
   * @param Array $attributes - additional attibutes to the script tag(s)
   * @return string - the script tag(s)
   */
  public function javascript($main_file, Array $attributes = Array()) {
    return $this->assetLinks("script", "JavascriptAsset", $main_file, $attributes);
  }
  
  /**
   * retrives file list which gets included in $main_file.
   * creates the specified tags out of it and returns them as string
   *
   * @access private
   * @param string $tagname     - which tag type should get created
   *                              for example: 'script' or 'link'
   * @param string $asset_class - for example: 'CSSAsset' or 'JavascriptAsset'§
   * @param string $main_file   - the file which gets read
   * @param string $attributes  - additional attribtues to the tags
   * @return string - the tag(s)
   */
  private function assetLinks($tagname, $asset_class, $main_file, Array $attributes = Array()) {
    $tags = Array();
    $asset_class = "Void\\" . $asset_class;
    $asset = new $asset_class($main_file);
    foreach($asset->getFileList() as $file) {
      $methodname = strtolower($tagname) . "Tag";
      $tags[] = $this->$methodname(BASEURL . str_replace(DS, "/", $asset->getDirectory() . DS . $file), $attributes)->output();
    }
    return implode("\n", $tags);
  }

  /**
   * create a form
   * 
   * @param mixed $model      the method of the form or ActiveRecord\Model
   * @param mixed $action     an action of a controller to send this form to
   * @param array $attributes additional attributes for the form (or the
   *                          callback if you don't have some)
   * @param mixed $callback   a callback function (first parameter is the form)
   * @access public
   * @return string  - the finished form as HTML
   */
  public function form($model, $action, $attributes = Array(), $callback = null) {
    // use POST as defailt method
    $method = "post";
    // use "get" if given instead of an model
    strtolower($method) === "get" && $method == "get";

    if($callback === null) {
      $callback = $attributes;
      $attributes = Array();
      // throw an exception if $callback isn't callable?
    }

    // create tje form tag
    $form = new HTML\FormTag($method, $action, $attributes);

    // give the model to the form if there is one
    if($model instanceof \ActiveRecord\Model) {
      $form->setModel($model);
    }
    
    // grab the output of the callback function and set it as content of the form
    ob_start();
    $callback($form);
    $contents = ob_get_contents();
    ob_end_clean();
    $form->setContent($contents);

    // return the finish html of the form
    return $form->output();
  }

  /**
   * define a placeholder inside of the template
   * which later gets replaced by the content specified using content_for
   *
   * @param string $variable 
   * @return string
   */
  public function yield($variable) {
    return "--" . YIELD_BOUNDARY . "(" . $variable . ")--";
  }

  /**
   * set the content for a placeholder indicated by a yield-call
   *
   * @param string $variable  - name of the yield placeholder
   * @param Closure $content  -  a closure which outputs the content for the placeholder
   */
  public function content_for($variable, $content) {
    ob_start();
    $content();
    $contents = ob_get_contents();
    ob_end_clean();
    $varname = "__yield_$variable";
    $this->$varname = $contents;
  }
  
  /**
   * Link to a certain Controller
   *
   * @param string $link_function (defined as 3rd parameter in config/routes.php)
   * @param Array $params params to link function
   * @return string
   * @see Router 
   */
  public function link($link_function, $params = null) {
    return Router::link($link_function, $params);
  }
  
  /**
   * properly escape a string for outputting it savely in a HTML document
   *
   * @param string $str
   * @return string
   */
  public function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  }

  /**
   * properly output a term in singular or plural according to $num
   *
   * @param $num int amount
   * @param string $singluar the term in the singular form (e.g. "person")
   * @param string $plural the term in the plural form (e.g. "people")
   * @return string 
   */
  public function plural($num, $singluar, $plural) {
    return $num . " " . ($num == 1 ? $singluar : $plural);
  }
}

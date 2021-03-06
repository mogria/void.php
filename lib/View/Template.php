<?php
/**
 * @author Mogria
 */

namespace Void;

use BadMethodCallException;
use Exception;

/**
 * A Template System
 */
class Template extends VirtualAttribute {

  /**
   * @var TemplateFinder
   */
  protected $template_finder;

  /**
   * @var Helper
   */
  protected $helper;

  /**
   * @var ViewRenderer
   */
  protected $template_renderer;

  /**
   * Constructor
   * @param string $flle
   * @param Array $initializers
   */
  public function __construct($file, Array $initializers = Array()) {
    $this->template_finder = new TemplateFinder();
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
    $this->template_finder->setFilespec($file);
    $this->template_renderer = $this->template_finder->getRenderer();
    $this->createHelper();
  }

  /**
   * creates and instance of an Helper for the current template file used
   * and stores it in the attribute $this->helper
   */
  private function createHelper() {
    $helpername = s($this->template_finder->getController())->camelize() . self::$config->helper_postfix;
    if(!class_exists(__NAMESPACE__ . "\\" . $helpername, true)) {
      $helpername = "ApplicationHelper";
    }
    $helpername = __NAMESPACE__ . "\\" . $helpername;
    $this->helper = new $helpername($this);
  }

  /**
   * returns the path to the template file which should get rendered
   *
   * @return string
   */
  public function getFile() {
    return $this->template_finder->getPath();
  }

  /**
   * calls the proper template renderer and renders the template_render
   *
   * @return string
   */
  public function render() {
    $this->template_renderer->setVariables($this->getReference());
    $this->helper->setViewRenderer($this->template_renderer);
    if($this->template_renderer instanceof HelpableRenderer) {
      $this->template_renderer->setHelper($this->helper);
    }
    return $this->template_renderer->render();
  }

  /**
   * returns the TemplateRenderer used to process the template file
   *
   * @return
   */
  public function getTemplateRenderer() {
    return $this->template_renderer;
  }
  
  /**
   * returns the helper which provides functions for templates which support them
   * if no Helper is available this method may also return null
   *
   * @return 
   */
  public function getHelper() {
    return $this->helper;
  }
}

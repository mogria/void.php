<?php
/**
 * @author Mogria
 */

namespace Void;

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
    return preg_replace(Array(
      '/\{>(.*?)\}/',
      '/\{\[(.*?)\}/',
      '/\{=(.*?)\}/',
      '/\{(.*?)\}/'
    ), Array(
      "<?php print(htmlspecialchars(\\1)) ?>",
      "<?php \\1->parse() ?>",
      "<?php print(\\1) ?>",
      "<?php \\1 ?>"
    ), $string);
  }

  /**
   * the template file set will be rendered. All the
   * variables set (by using the method's from virtual attribute)
   * are available in the Template file.
   *
   * @return string the output of the template
   */
  public function render() {
    extract($this->toArray());
    ob_start();
    eval( <<<_VOID_TEMPLATE
?>
{$this->parse(file_get_contents($this->file))}
_VOID_TEMPLATE
    );
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
  }
}
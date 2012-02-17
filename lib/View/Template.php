<?php
/**
 * @author Mogria
 */

namespace Void;

class Template extends VirtualAttribute {
  protected $file;
  
  public function __construct($file, Array $initializers = Array()) {
    $this->setFile($file);
    $this->_ = $initializers;
  }
  
  public function setFile($file) {
    $finder = new TemplateFinder($file);
    $this->file = $finder->getPath();
  }
  
  public function getFile() {
    return $this->file;
  }
  
  public function parse($string) {
    return preg_replace(Array(
      '/\{>(.*?)\}/',
      '/\{\[(.*?)\}/',
      '/\{=(.*?)\}/',
      '/\{(.*?)\}/ms'
    ), Array(
      "<?php print(htmlspecialchars(\\1)) ?>",
      "<?php \\1->parse() ?>",
      "<?php print(\\1) ?>",
      "<?php \\1 ?>"
    ), $string);
  }
  
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
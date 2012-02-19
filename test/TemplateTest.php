<?php

namespace Void;

require_once 'lib/Exceptions/VoidException.php';
require_once 'lib/Exceptions/InexistentFileException.php';
require_once 'lib/View/VirtualAttribute.php';
require_once 'lib/View/Template.php';
require_once 'lib/View/TemplateFinder.php';

class TemplateTest extends \PHPUnit_Framework_TestCase {
  protected $template;
  public function setUp() {
    $this->template = new Template('test/Views/test.tpl', Array('included' => new Template('test/Views/included.tpl')));
  }

  public function testParse() {
    $expected_output = <<<TEXT
<h1>This is just a Test Template</h1>
<?php for(\$x = 0; \$x < 2; \$x++): ?>
<p>This is just some random Text to Test the Template Engine. <?php print(date('d.m.Y', 0)) ?></p>
<?php endfor ?>
<?php print(\$included->render()) ?>
<p>encoded: <?php print(htmlspecialchars("<>\"")) ?></p>
TEXT;
    $this->assertEquals($expected_output, $this->template->parse(file_get_contents($this->template->getFile())));
  }

  public function testRender() {
    $expected_output = <<<TEXT
<h1>This is just a Test Template</h1>
<p>This is just some random Text to Test the Template Engine. 01.01.1970</p>
<p>This is just some random Text to Test the Template Engine. 01.01.1970</p>
<p>this is an other template</p><p>encoded: &lt;&gt;&quot;</p>
TEXT;
    $this->assertEquals($expected_output, $this->template->render());
  }
}
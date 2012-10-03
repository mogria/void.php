<?php

namespace Void;


class TemplateTest extends \PHPUnit_Framework_TestCase {
  protected $template;

  public static function setUpBeforeClass() {
    require 'test/test_voidphp_boot.php';
  }

  public function setUp() {
    $this->template = new Template('layout/test'/*, Array('included' => new Template('included')) */);
  }

  public function testRender() {
    $expected_output = <<<TEXT
<h1>This is just a Test Template</h1>

<p>This is just some random Text to Test the Template Engine. 01.01.1970</p>

<p>This is just some random Text to Test the Template Engine. 01.01.1970</p>

<p>this is an other template</p>
<p>encoded: &lt;&gt;&quot;</p>
TEXT;
    $this->assertEquals($expected_output, $this->template->render());
  }
}

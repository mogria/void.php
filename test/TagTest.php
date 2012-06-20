<?php

namespace Void;

require_once 'lib/View/VirtualAttribute.php';
require_once 'lib/HTML/Tag.php';

class TagTest extends \PHPUnit_Framework_TestCase {
  protected $tag;

  public function setUp() {
    $this->tag = new HTML\Tag("p", "This is a test Text with other <b>Tags</b>", array('class' => 'small'));
  }

  public function testOutput() {
    $this->assertEquals("This is a test Text with other <b>Tags</b>", $this->tag->getContent());
    $this->assertEquals("<p class=\"small\">This is a test Text with other <b>Tags</b></p>", $this->tag->output());
    $this->assertFalse(isset($this->tag->disabled));
    $this->tag->disabled = null;
    $this->assertTrue(isset($this->tag->disabled));
    $this->assertEquals("<p class=\"small\" disabled>This is a test Text with other <b>Tags</b></p>", $this->tag->output());
    $this->assertTrue(isset($this->tag->class));
    unset($this->tag->class);
    unset($this->tag->disabled);
    $this->assertFalse(isset($this->tag->class));
    $this->assertEquals("<p>This is a test Text with other <b>Tags</b></p>", $this->tag->output());
    $this->tag->setTagname("H1");
    $this->assertEquals("h1", $this->tag->getTagname());
    $this->assertEquals("<h1>This is a test Text with other <b>Tags</b></h1>", $this->tag->output());
    $this->tag->setContent(null);
    $this->assertEquals(null, $this->tag->getContent());
    $this->assertEquals("<h1 />", $this->tag->output());
  }
}

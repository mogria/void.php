<?php

namespace Void\HTML;
use \ActiveRecord\Model;


class SelectTag extends InputTag {
  protected static $tag_name = "select";
  protected $label_system;

  /**
   * Constructor
   *
   * @param string $name -  the name of the form field
   * @param string $content - @see setContent()
   * @param Array $label - pass Array() if you don't pass an array of models to
   *                       $content. If you pass an array of models to $content.
   *                       this param describes hwo the label of the option tags
   *                       is made. The first element of the Array is an format string
   *                       passed to sprintf(). The following elements are colum names
   *                       from which the data gets pulled when sprintf() is called
   *                       Bsp.
   *                       Array('%s - %s', 'id', 'name');
   *                       label -> '15 - Admin'
   * @param Array $attributes - additional attributes to the select tag
   */
  public function __construct($name, $content, $label = Array(), Array $attributes = Array()) {
    $this->label_system = $label;
    // call the parent constructor, this will call setContent()
    // and do some other important stuff
    parent::__construct($name, "select", $content, $attributes);
    // delete the type attribute
    $this->exists('type') && $this->delete('type');
  }

  /**
   * you cant set the type. no seriously you can't. don't even try.
   *
   * @return false
   */
  public function setType($new_type) {
    return false;
  }

  /**
   * returns the type of this input field
   * 
   * @return string "select"
   */
  public function getType() {
    return "select";
  }

  /**
   * set the content of the select field (the options)
   *
   * @param Array $content - an array which contains ...
   *                          - value
   *                          - value => label
   *                          - an object instanceof \ActiveRecord\Model
   *                          Bsp.
   *                          Array('pizza', 'fries');
   *                          Array(2 => 'pizza', 5 => 'fries');
   *                          Array(new User(Array('name' => 'Trollinger'),
   *                            new User(Array('name' => 'admin'));
   * @return void
   */
  public function setContent($content) {
    $this->content = Array();
    $keys = array_values($content) === $content;
    foreach($content as $key => $value) {
      if($value instanceof Model) {
        $label = array_values($this->label_system);
        $format = array_shift($label);
        foreach($label as &$label_value) {
          $label_value = $value->$label_value;
        }
        $this->content[] = new OptionTag($value->{$value->get_primary_key(true)}, call_user_func_array('sprintf', array_merge(array($format), $label)));
      } else {
        if($keys) {
          $this->content[] = new OptionTag($value);
        } else {
          $this->content[] = new OptionTag($key, $value);
        }
      }
    }
  }

  /**
   * returns all the option tags in the select tag (seperated by \n)
   *
   * @return string - 
   */
  public function getContent() {
    // get the HTML of each option tag and merge them together with \n in between
    return implode("\n", array_map(function($element) {
      return $element->output();
    }, $this->content));
  }
}

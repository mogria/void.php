<?php

namespace Void;

/**
 * All the Helper classes extend this class
 * All method calls will be forwarded to the template object
 */
class HelperBase extends VirtualAttribute {

  /**
   * @var Template
   * @access protected
   */
  protected $template;
  
  /**
   * Constructor
   *
   * @param Template $template
   */
  public function __construct(Template $template) {
    $this->template = $template;
    $this->setReference($this->template->getReference());
  }

  /**
   * Forwards all the method calls to the template object
   *
   * @param string $method - the name of the method to call
   * @param Array $args    - the arguments for the method call
   * @return mixed
   */
  public function __call($method, $args) {
    return $this->template->__call($method, $args);
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
      $tags[] = $this->$methodname(BASEURL . $asset->getDirectory() . DS . $file, $attributes)->output();
    }
    return implode("\n", $tags);
  }
}

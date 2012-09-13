<?php

namespace Void\Response\Format;
use Void\Response\Format;

/**
 * represents a Json response
 *
 */
class Json implements Format {

  /**
   * holds data of the request returned
   * @var Array
   */
  protected $data;

  /**
   * constructor
   *
   * @param Array $data - initialize the $data property
   */
  public function __construct(Array $data) {
    $this->data = $data;
  }
  
  /**
   * returns the mime type of json 'text/json'
   */
  public function getMimeType() {
    return 'application/json';
  }

  /**
   * returns the .json file extension
   * @return string
   */
  public function getFileExtenison() {
    return 'json';
  }

  /**
   * getter for $data property
   * @return <type>
   */
  public function getData() {
    return $this->data;
  }

  /**
   * setter for $data property
   * @param <type> $data
   */
  public function setData(Array $new_data) {
    $this->data = $new_data;
  }

  /**
   * json_encode() the $data and return it
   *
   * @return string
   */
  public function format() {
    return json_encode($this->data);
  }
}

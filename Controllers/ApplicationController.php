<?php

namespace Void;

/* All the Controllers should extend this Controller,
 so all methods you write into this class are accessible
 from all the Controller */

abstract class ApplicationController extends ControllerBase {

  /* this method will always be called
   before the action of the controller is called
   
   In here you can initialize some variables for the view, or do other things.
   For instance:
   <code>
   // set the default title
   $this->title = "My Mega Site";
   </code>

   If you want to overwrite this method by your own Controller
   do it like follows:
   
   <code>
   class MyController extends ApplicationController {
     public function initialize() {
       // do some stuff ...

       parent::initialize(); // don't forget this line
     }
   }
   </code>
   */
  public function initialize() {
  }
}
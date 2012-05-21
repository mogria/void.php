<?php

namespace Void;

/* all the methods you write in here are accessible
 in the template. For instance:

 helper method:
 <code>
 public function copyright() {
   $start_year = 2012;
   return "copyright &copy; " . ("$start_year" == date("Y") ? date("Y") :  $start_year . " - " . date("Y")) . " by &lt;company_name&gt;";
 }
 </code>

 template:
 <code>
 {=:copyright()} 
 </code>
 the above template calls the helper method `copyright` */ 

class ApplicationHelper extends HelperBase {
}

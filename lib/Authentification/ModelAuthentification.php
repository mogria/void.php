<?php

namespace Void;
use \ActiveRecord\Model;

abstract class ModelAuthentification extends Model implements Authentification {
 public function reload() {
   $class = get_called_class($this);
   $loaded = $class::find_by_id($model->id);
   $self = $this;
   if($loaded != null) {
     $update_func = function() use (&$self, $loaded) {
       $self = $loaded;
     };
     $update_func();
     return true;
   } else {
     return false;
   }
 }
}

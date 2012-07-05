<?php

namespace Void;

class Authentification extends VoidBase {
  
 public function reload($model) {
   $class = get_class($model);
   $loaded = $class::find_by_id($model->id);
   return $loaded;
 }

 public function login($model) {
   if($model instanceof Authorizeable) {
     $_SESSION['role'] = $model->login();
   }
 }

 public function logout($model) {

 }
}

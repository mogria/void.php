{if($object instanceof \ActiveRecord\Model && $object->errors && !$object->errors->is_empty()):}
<div class="error">
<h4>The following errors occured:</h4>
  <ul>
  {foreach($object->errors as $error):}
    <li>{>$error}</li>
  {endforeach}
  </ul>
</div>
{endif}

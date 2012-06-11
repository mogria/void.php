{=:form('post', $action, function($f) use ($category){}
  {$f->setModel($category)}
  <fieldset>
    <legend>{>$category->id === null ? "Create" : "Edit"} a Category</legend>
    <div>
      {=$f->label("name")}
      {=$f->text_field("name")}
    </div>

    <div>
      {=$f->label("parent category")}
    </div>

    <div>
      {=$f->submit("Save")}
    </div>
  </fieldset>
{})}

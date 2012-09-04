{=:render('layout/_errors', Array('object' => $post))}
{=:form('post', $action, function($f) use ($post, $categories){}
  {$f->setModel($post)}
  <fieldset>
    <legend>{>$post->id === null ? "Create" : "Edit"} a Post</legend>
    <div>
      {=$f->label("title")}
      {=$f->text_field("title")}
    </div>

    <div>
      {=$f->label("content")}
      {=$f->text_area("content", Array('rows' => '5', 'cols' => '40'))}
    </div>

    <div>
      {=$f->label("taglist")}
      {=$f->text_field("taglist")}
    </div>

    <div>
      {=$f->label("categories")}
      {foreach($categories as $category):}
        <input type="checkbox" name="categories[]" value="{>$category->id}" {=in_array($category, is_array($post->categories) ? $post->categories : array()) ? 'checked' : ''}/>{>$category->name}
      {endforeach}
    </div>

    <div>
      {=$f->submit("Save")}
    </div>
  </fieldset>
{})}

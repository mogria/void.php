{foreach($categories as $category):}
  {=:render('categories/_category.tpl', Array('category' => $category))}
{endforeach}

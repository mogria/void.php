{foreach($posts as $key => $post):}
  {if($key):}
  <hr />
  {endif}
  {=:render('posts/_post', Array('post' => $post))}
{endforeach}

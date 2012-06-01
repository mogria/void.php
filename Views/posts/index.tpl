{foreach($posts as $key => $post):}
  {if($key):}
  <hr class="article-separator"/>
  {endif}
  {=:render('posts/_post', Array('post' => $post))}
{endforeach}

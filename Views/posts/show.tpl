{if($post === null):}
<h1>404 Not Found</h1>
No post found
{else:}
{=:render('posts/_post', Array('post' => $post))}
{endif}

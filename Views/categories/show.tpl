{if($category):}
{=:render('categories/_category', Array('category' => $category))}
{=:render('posts/index', Array('posts' => $category->posts))}
{else:}
<h1>404 Not Found</h1>
No category found
{endif}

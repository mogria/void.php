{=:render('categories/_category', Array('category' => $category))}
{=:render('posts/index', Array('posts' => $category->posts))}

{if($tag === null):}
<h1>404 Not Found</h1>
No post found
{else:}
<div class="box tag-title">
  <h3>{>$tag->name}</h3>
</div>
{=:render('posts/index', Array('posts' => $tag->posts))}
{endif}

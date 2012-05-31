<article class="post">
  <h1 class="title">{>$post->title}</h1>
  <div class="content">{>$post->content}</div>
  <div class="meta-data">
    Written by <span class="author">{>$post->user->fullname}</span>
    at <span class="created">{>$post->created_at->format("d-m-Y H:i:s")}</span>
    {if($post->created_at != $post->updated_at):}
      last updated at <span class="updated">{>$post->updated_at->format("d-m-Y H:i:s")}</span>
    {endif}
  </div>
<article>


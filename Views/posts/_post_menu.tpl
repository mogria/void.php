<menu class="box">
  <li>{=:aTag("Delete this post", 'posts/delete/' . $post->id, Array('onclick' => 'return confirm("Do you really want to delete this post?")'))}
  <li>{=:aTag("Edit this post", 'posts/edit/' . $post->id)}
</menu>

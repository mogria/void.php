<menu class="box">
  <li>{=:aTag("Delete this post", $this->link_post_delete($post->id), Array('onclick' => 'return confirm("Do you really want to delete this post?")'))}
  <li>{=:aTag("Edit this post", $this->link_post_edit($post->id))}
</menu>

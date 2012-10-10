<article class="comment">
  <div class="comment-sidebar box">
    {if($comment->user_id):}
      <a href="{>:link_user($comment->user_id)}">{>$comment->fullname}</a>
    {else:}
      {>$comment->fullname}
    {endif}
    {>$comment->created_at->format('d/m/Y')}<br />
    {>$comment->created_at->format('H:i')}
  </div>

  <div class="comment-content">
    {>$comment->content}
  </div>
</article>

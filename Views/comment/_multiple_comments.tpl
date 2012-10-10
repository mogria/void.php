<h3>{>:plural(count($comments), "Comment", "Comments")}</h3>
{foreach($comments as $comment):}
  <hr />
  {=:render('comment/_comment', Array('comment' => $comment))}
{endforeach}

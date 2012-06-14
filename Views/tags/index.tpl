{foreach($tags as  $tag):}
  {=:aTag($tag->name . ' (' . count($tag->posts) . ')', Array('tags', 'show', $tag->name))}
{endforeach}

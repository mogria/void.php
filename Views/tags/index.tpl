{foreach($tags as  $tag):}
  {=:aTag($tag->name . ' (' . count($tag->posts) . ')', $this->link_tag($tag->name))}
{endforeach}

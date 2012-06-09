<div class="box category-title">
  <h3 class="span-8">{=:aTag($category->name, Array('categories', 'show', $category->id))}</h3>
  <menu class="span-5 last category-menu">
    <li>{=:aTag('bearbeiten', Array('categories', 'edit', $category->id))}</li>
    <li>{=:aTag('delete', Array('categories', 'delete', $category->id))}</li>
  </menu>
  <div class="clear"></div>
</div>

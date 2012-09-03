<div class="box category-title">
  <h3 class="span-8">{=:aTag($category->name, $this->link_category($category->id))}</h3>
  <menu class="span-5 last category-menu">
    <li>{=:aTag('bearbeiten', $this->link_params('categories', 'edit', $category->id))}</li>
    <li>{=:aTag('delete', $this->link_params('categories', 'delete', $category->id), Array('onclick' => 'return confirm("Do you really want to delete this category?")'))}</li>
  </menu>
  <div class="clear"></div>
</div>

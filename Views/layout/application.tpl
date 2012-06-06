<!doctype html>
<html>
  <head>
    <title>Your Page Title</title>
    <meta charset="utf-8" />
    {=:stylesheet('blueprint/screen', Array('media' => 'screen, projection'))}
    {=:stylesheet('blueprint/print', Array('media' => 'print'))}
    <!--[if lt IE 8]>
    {=:stylesheet('blueprint/ie', Array('media' => 'screen, projection'))}
    <![endif]-->
    {=:stylesheet('application')}
    {=:javascript('application')}
  </head>
  <body>
    <div id="wrapper" class="container">
      <header class="push-1 span-22">
        <h1>test-blog</h1>
      </header>
      <nav class="push-1 span-22">
        <ul>
          <li>{=:aTag("Home", null)}</li>
          <li>{=:aTag("About", "pages/about")}</li>
          <li>{=:aTag("Categories", "categories")}</li>
        </ul>
      </nav>
      <section class="push-1 span-15 clear">
        {=:render('layout/_flash')}
        {[$_content} 
      </section>
      <aside class="push-2 span-6 last">
        <h3>Actions</h3>
        <ul>
          <li>{=:aTag('create a post', Array('posts', 'new'))}</li>
          <li>{=:aTag('create a category', Array('categories', 'new'))}</li>
        </ul>
      </aside>
      <footer class="clear">
        &copy; Copyright {>:copyright_year()} by Mogria | powered by <a href="http://github.com/mogria/void.php" target="_blank">void.php</a>
      </footer>
  </body>
</html>

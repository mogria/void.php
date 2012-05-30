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
          <li>{=:aTag("About", null)}</li>
          <li>{=:aTag("Categories", null)}</li>
        </ul>
      </nav>
      <section class="push-1 span-16 clear">
        section
        {[$_content} 
      </section>
      <aside class="span-7 last">
        <h3>test</h3>
      </aside>
      <footer class="clear">
        &copy; Copyright {>:copyright_year()} by Mogria | powered by <a href="http://github.com/mogria/void.php" target="_blank">void.php</a>
      </footer>
  </body>
</html>

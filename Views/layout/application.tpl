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
        <ul class="navimenu">
          <li>{=:aTag("Home", $this->link_root())}</li>
          <li>{=:aTag("About", $this->link_about())}</li>
          <li>{=:aTag("Categories", $this->link_categories())}</li>
          <li>{=:aTag("Tags", $this->link_tags())}</li>
        </ul>
        
        <ul class="usermenu right">
        {if(Session::user()->role->login):}
          <li>{=:aTag("Profile", $this->link_user())}</li>
          <li>{=:aTag("Logout", $this->link_logout())}</li>
        {else:}
          <li>{=:aTag("Login", $this->link_login())}</li>
        {endif}
        </ul>
      </nav>
      <section class="push-1 span-15 clear">
        {=:render('layout/_flash')}
        {[$_content} 
      </section>
      <aside class="push-2 span-6 last">
        <h3>Actions</h3>
        <ul>
          <li>{=:aTag('create a post', $this->link_new_post())}</li>
          <li>{=:aTag('create a category', $this->link_new_category())}</li>
        </ul>
      </aside>
      <footer class="clear">
        &copy; Copyright {>:copyright_year()} by Mogria | powered by <a href="http://github.com/mogria/void.php" target="_blank">void.php</a>
      </footer>
  </body>
</html>

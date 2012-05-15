<!doctype html>
<html>
  <head>
    <title>{=:title("Void.php", $title)}</title>
    <meta charset="utf-8" />
    {=:stylesheet('blueprint/screen', Array('media' => 'screen, projection'))}
    {=:stylesheet('blueprint/print', Array('media' => 'print'))}
    <!--[if lt IE 8]>
    {=:stylesheet('blueprint/ie', Array('media' => 'screen, projection'))}
    <![endif]-->
    {=:stylesheet('application')}
  </head>
  <body>
    <div id="wrapper" class="container">
      <div class="container">
        {=:render('layout/_header')}
        <hr />
        <section class="span-16">
          {=:render('layout/_flash')}
          {[$_content}
        </section>
        <aside class="span-8 last">
          <h1>by the way ...</h1>
          <p class="box">Random text, Random text, Random text, Random text, Random text, Random text, Random text, Random text</p>
        </aside>
        <hr />
        {=:render('layout/_footer')}
      </div>
    </div>
  </body>
</html>

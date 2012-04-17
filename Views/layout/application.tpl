<!doctype html>
<html>
  <head>
    <title>{=isset($title) ? $title . " - " : ""}Void.php</title>
    <meta charset="utf-8" />
    {=$this->linkTag('blueprint/screen', Array('media' => 'screen, projection'))}
    {=$this->linkTag('blueprint/print', Array('media' => 'print'))}
    <!--[if lt IE 8]>
    {=$this->linkTag('blueprint/ie', Array('media' => 'screen, projection'))}
    <![endif]-->
    {=$this->linkTag('application')}
  </head>
  <body>
    <div id="wrapper" class="container">
      <div class="container">
        {=$this->render(Array('layout', '_header'))}
        <hr />
        <section class="span-16">
          {[$_content}
        </section>
        <aside class="span-8 last">
          <h1>by the way ...</h1>
          <p class="box">Random text, Random text, Random text, Random text, Random text, Random text, Random text, Random text</p>
        </aside>
        <hr />
        {=$this->render(Array('layout', '_footer'))}
      </div>
    </div>
  </body>
</html>

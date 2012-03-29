<!doctype html>
<html>
  <head>
    <title>Void.php</title>
    <meta charset="utf-8" />
    {=$this->linkTag('stylesheets/blueprint/screen.css', Array('media' => 'screen, projection'))}
    {=$this->linkTag('stylesheets/blueprint/print.css', Array('media' => 'print'))}
    <!--[if lt IE 8]>
    {=$this->linkTag('stylesheets/blueprint/ie.css', Array('media' => 'screen, projection'))}
    <![endif]-->
    {=$this->linkTag('stylesheets/style.css')}
  </head>
  <body>
    <div id="wrapper" class="container">
      <div class="container">
        {=$this->render(Array('layout', '_header'))}
        <hr />
        <section class="span-16">
          {[$content}
        </section>
        <aside class="span-8 last">
          <h1>by the way ...</h1>
          <p class="box">Random text, Random text, Random text, Random text, Random text, Random text, Random text, Random text</p>
        </aside>
        {=$this->render(Array('layout', '_footer'))}
      </div>
    </div>
  </body>
</html>

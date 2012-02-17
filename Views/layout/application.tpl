<!doctype html>
<html>
  <head>
    <title>Void.php</title>
    <meta charset="utf-8" />
    <link href="{>BASEURL}stylesheets/blueprint/screen.css" rel="stylesheet" type="text/css" media="screen, projection" />
    <link href="{>BASEURL}stylesheets/blueprint/print.css" rel="stylesheet" type="text/css" media="print" />
    <!--[if lt IE 8]>
    <link href="{>BASEURL}stylesheets/blueprint/ie.css" type="text/css" media="print">
    <![endif]-->
    <link href="{>BASEURL}stylesheets/style.css" rel="stylesheet" type="text/css" />
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
        <footer class="span-24 last">
          <p>Powered by <a href="http://github.com/mogria/void.php">Void.php</a></p>
        </footer>
      </div>
    </div>
  </body>
</html>
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
    <!-- This file is located at `Views/layout/application.tpl` -->
  </head>
  <body>
    <!-- this is where the current page is displayed, so better don't delete it -->
    {[$content} 
  </body>
</html>
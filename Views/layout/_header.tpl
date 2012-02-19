<header class="span-24 last">
  <div id="headerimage" class="span-12">
    <img src="{>BASEURL}images/void.php.png" alt="void.php - change the way you write php" title="void.php - change the way you write php" />
  </div>
  <nav class="span-12 last">
    <ul>
      <li>{=$this->aTag('Home', array())}</li>
      <li>{=$this->aTag('About', array('pages', 'about'))}</li>
      <li>{=$this->aTag('Contact', array('pages', 'contact'))}</li>
      <li>{=$this->aTag('Help', array('pages', 'help'))}</li>
      <li>{=$this->aTag('Github Repository', 'http://github.com/mogria/void.php', Array('target' => '_blank'))}</li>
    </ul>
  </nav>
</header>
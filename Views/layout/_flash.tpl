{Flash::show(function($flash_message){}
  <div class="{>$flash_message->getType()}">{>$flash_message->getMessage()}</div>
{});}

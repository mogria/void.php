{if($this->result === null):}
{>$this->calc}
{else:}
{>$this->calc} = {>$this->result}
{endif}

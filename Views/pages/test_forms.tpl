{=:form("get", "#", function($f) use ($title){}
  <fieldset>
    <legend>Test Formular</legend>
    <p>{=$f->label('Test a simple Textbox:', 'test_text')}<br />
    {=$f->text_field('test_text')}</p>
    <p>{=$f->label('Test a Password Box:', 'test_password')}<br />
    {=$f->password('test_password')}</p>
    <p>{=$f->label('Test a Text Area:', 'test_area')}<br />
    {=$f->text_area('test_area')}</p>
    {=$f->submit('Save')}</p>
  </fieldset>
{})}

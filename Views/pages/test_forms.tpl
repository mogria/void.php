{=:form("get", "#", function($f) use ($title){}
  <fieldset>
    <legend>Test Formular</legend>
    <p>{=$f->label('Test a simple Textbox:', 'test_text')}<br />
    {=$f->text_field('test_text')}</p>

    <p>{=$f->label('Test a Password Box:', 'test_password')}<br />
    {=$f->password_field('test_password')}</p>

    <p>{=$f->label('Test a Text Area:', 'test_area')}<br />
    {=$f->text_area('test_area')}</p>

    <p>{=$f->label('Test a Select:', 'test_select')}<br />
    {=$f->select('test_select', Array(1 => 'Trolls', 2 => 'Trollinger', 3 => 'Trololol'))}</p>
    {=$f->submit('Save')}</p>
  </fieldset>
{})}

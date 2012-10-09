{=:render('layout/_errors', Array('object' => $user))}
{=:form('post', :link_register(), function($f) use ($user){}
  {$f->setModel($user)}
  <fieldset>
    <legend>Register</legend>

    <div>
      {=$f->label("name")}
      {=$f->text_field("name")}
    </div>

    <div>
      {=$f->label("text_password")}
      {=$f->password_field("text_password")}
    </div>

    <div>
      {=$f->label("text_password_confirm")}
      {=$f->password_field("text_password_confirm")}
    </div>

    <div>
      {=$f->label("email")}
      {=$f->text_field("email")}
    </div>

    <div>
      {=$f->submit("Save")}
    </div>
{})}

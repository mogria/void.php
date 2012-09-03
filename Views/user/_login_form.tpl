{=:render('layout/_errors', Array('object' => $user))}
{=:form('post', $this->link_login(), function($f) use ($user){}
  {$f->setModel($user)}
  <fieldset>
    <legend>Login</legend>
    <div>
      {=$f->label("Username")}
      {=$f->text_field("name")}
    </div>

    <div>
      {=$f->label("Password")}
      {=$f->password_field("text_password")}
    <div>

    <div>
      {=$f->submit("Login")}
    </div>
  </fieldset>
{});}

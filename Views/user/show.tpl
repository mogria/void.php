{if(Session::user() && $user->id === Session::user()->id):}
<h1>Your Profile</h1>
{else:}
<h1>{>$user->fullname . "'s"} Profile</h1>
{endif}
<table>
  <tr>
    <th>username</th>
    <td>{>$user->name}</td>
  </tr>
  <tr>
    <th>real name</th>
    <td>{>$user->fullname}</td>
  </tr>
  <tr>
    <th>status</th>
    <td>{>$user->status ? $user->status : "-"}</td>
  </tr>
  <tr>
    <th>description</th>
    <td>{>$user->description ? $user->description : "-"}</td>
  </tr>
  <tr>
    <th>administrator</th>
    <td>{>$user->admin ? "yes" : "no"}</td>
  </tr>
  <tr>
    <th>Last Login</th>
    <td>{>$user->last_login !== null ? $user->last_login->format('d/m/Y H:i') : "never"}</td>
  </tr>
</table>

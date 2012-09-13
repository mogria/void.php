<?php

namespace Void;

/* in here you can configure which request goes to which controller
   
   be aware of: only the part behind index.php and no GET parameters
   get parsed,

   Example:
   Request
   http://example.com/subdir/index.php/some/stuff/15?someparam=23&stuff

   only the following part matters:
   /some/stuff/15
   
   if you wanted to match _only_ the url /user/show/15 and map it to your
   overview controller and the action user, you'd have to put the following
   line into this file:

   $route->match('/user/show/15', '/overview/user/15');

   Note: the further on top the $route->match() call is the higher it's priority
   Note: it is not checked if the controller you are mapping your request to even
         exists. If it doesn't the dispather_default_controller in your configuration
         is used.

   With the route above only the user with ID 15 can be shown, but all the users
   should be accessible via this route. This is how you'd set the route up:

   $route->match('/user/show/:id', '/overview/user/:id);

   In this case :id is something like a variable. In this case it stores all the
   stuff after  /user/show/ in it until the next slash or the end of the string
   occures. But we only want numbers. Simply do the following:

   $route->match('/user/show/:[0-9]id', '/overview/user/:id);
   
   Now only numbers can be stored inside :id.

   But you may want to display multiple users per page. To do this you also need multiple
   id's. In this case you can do the following:

   $route->match('/user/show/:[0-9]+id', '/overview/user/:id);

   this will accept requests like

   /user/show/15
   /user/show/15/16
   /user/show/15/16/17
   ...

   but not
   /user/show
   /user/show/

   in your action you can now make use of func_get_args() to get all the ids.

   let's say you want at maximum 2 id's and you also want to accept the request if none 
   is given:

   $route->match('/user/show/:[0-9]{,2}id', '/overview/user/:id);

   As you can tell this is a regex like syntax. The Code above will accept the
   following requests:

   /user/show
   /user/show/15
   /user/show/15/16

   but not
   /user/show/15/16/17
   /user/show/15/16/17/18
   ...

   For some reason you want to seperate your ids by a dash (-) and not by a /. You
   can do this:

   $route->match('/user/show/:[0-9]{,2}-id', '/overview/user/:id);

   Note: the character has to be a non-alphanumeric char.

   This will accept the following requests:

   /user/show
   /user/show/15
   /user/show/15-16

   but not 

   /user/show/15-16-15
   /user/show/15-16-17-18
   /user/show/15/16

   
   Create Links to your routes:

   Routes are cool & stuff but this won't give you any benefit you can't link
   dynamicly to them. As the third parameter of $route->match() you can specify the
   name of the link function. If you don't specifiy the this it will be generated
   automaticly out of the first parmeter (all the non alphanumeric chars wil be
   removed and replaced by a single _ ('/user/show/:[0-9]+id' will result in
   'user_show_0_9_id' as link function). If youre inside a template you can 
   call the link function using the $this->link_ as prefix. If you wanted to call the 
   'user_show_0_9_id' you would to the following inside of a template:


   $this->link_user_show_0_9_id( // ....

   Some link function also take arguments. The number of argument depends on the number 
   of variables inside of the first param given to $route->match(). For Example, our
   'user_show_0_9_id' link-function would take one parameter, because we used one variable
   (:id).

   If you need to call this function not inside of the template you simply call
   Router::link_<your_link_function>() (without the < > 's).

   A little example: (your void.php application is located at http://example.com/subdir/)

   $route->match('/', '/static/', 'root');
   // link function is called 'root', takes no arguments
   // example call: $this->link_root(); -> '/subdir/'
   
   $route->match('/about', '/static/about');
   // link function is called 'about', takes no arguments
   // example call: $this->link_about(); -> '/subdir/index.php/about'
   
   $route->match('/show/:id', '/posts/show/:id');
   // link function is called 'show_id', takes one argument
   // example call: $this->link_show_id(15); -> '/subdir/index.php/show/15'

   $route->match('/compare/:id1/:id2', '/user/compare/:id1/:id2', 'compare');
   // link function is called 'compare', takes two argument
   // example call: $this->link_compare(15, 23); -> '/subdir/index.php/compare/15/23'


   $route->match('/delete/:+-id', '/user/delete/:id');
   // link function is called 'delete_id', takes one argument (needs to be an array)
   // example call: $this->link_delete_id(array(15, 23, 32));
   // -> '/subdir/index.php/delete/15-23-32'

   */

Router::configure(function($route) {
  // pages/index should handle the start page
  $route->match('/', '/pages', 'root');

  // make it possible to make some calculations using the url
  $route->match('/:[0-9\.]++numbers', '/pages/math/+/:numbers', 'add');
  $route->match('/:[0-9\.]+-numbers', '/pages/math/-/:numbers', 'sub');
  $route->match('/:[0-9\.]+*numbers', '/pages/math/*/:numbers', 'mul');
  $route->match('/:[0-9\.]+:numbers', '/pages/math/:/:numbers', 'div');

  // we dont want /pages/ in the url
  $route->match('/about', '/pages/about', 'about');
  $route->match('/get_involved', '/pages/get_involved');
  $route->match('/help', '/pages/help');
  $route->match('/test_forms', '/pages/test_forms');
  $route->match('/params', '/pages/params');
  $route->match('/math', '/pages/math');
  $route->match('/text', '/pages/text');
  $route->match('/json', '/pages/json');

  // fallback route
  $route->match('/:+parts', '/:parts');
});

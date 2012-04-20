---
layout: default
permalink: /getting-started.html
title: Getting Started
---

# Things you should already know

## HTML & CSS

If you don't know HTML & CSS you're definitely at the wrong place.

## PHP & OOP

You should understand how PHP works and know how to programm object oriented.

## MVC

You should basicly know what MVC is, because void.php is based on the MVC pattern. At least take a look at the [Wikipedia Article](http://en.wikipedia.org/wiki/MVC)

# Installation

To install this you first need to download the source. You can either download an archive file from the start page, or clone the git repo over @ github.

## Downloading the archive

Go to the start pagea and select the proper archive type in the download section. If you have sucessfully downloaded the archive file, extract it somewhere inside the webroot.
Navigate the browser to the location you have extracted the archive. And here you have it ;-).

## Cloning the git-repository

Simply enter this command into your command line to download void.php

    $ git clone --recursive https://github.com/mogria/void.php

The `--recursive` parameter is needed, because void.php uses git submodules (git repositories inside of git repositories)..

# Small Introduction

## Folder structure

    Void.php
     |- config/
     |  |- consts.php
     |  |- default_configuration.php
     |  `- environments.php
     |- Controllers/
     |  `- PagesController.php
     |- Views/
     |  |-layout/
     |  `-pages/
     |- Models/
     |- images/
     |- javascripts/
     |- stylesheets/
     |  |- blueprint/
     |  |- layout/
     |  |- application.css
     |  `- style.css
     |- scripts/
     |  `- generate*
     |- test/
     |- lib/
     |- autoload.php
     |- index.php
     |- LICENSE
     `- README.md

### config/

All the configuration files are stored in here. But don't worry, you only have to set up the datebase Connection.


### config/consts.php

Some constants needed in the Framework are defined here. Better not mess with this file if you don't exactly know what you're doing.

### config/default_configuration.php

Some values which need to be initialized are defined in here. You can overwrite them in the `environments.php` file. So, don't mess with this file.


### config/environments.php

Here you can do your configuration. First of all you can define in which environment you're in by setting the `ENVIRONMENT` constant to an other value. There are three diffrent environments:

    +-------------+-------------------------------------------------------------+
    | development | Set the constant to this value if you are                   |
    |             | in development. This basically means no caching & all       |
    |             | error messages are displayed.                               |
    +-------------+-------------------------------------------------------------+
    | test        | At the moment there is not much difference between          |
    |             | test & development.                                         |
    +-------------+-------------------------------------------------------------+
    | production  | Set the constant to this value if the application is in     |
    |             | production. No Error messages and   caching is on.          |
    +-------------+-------------------------------------------------------------+

In this file there are also 4 sections in where you can do some configuration. One for each environment, and the first section is for all environments.

#### How the configuration works

The configurations are made as followings and can be done for every class which extends the `VoidBase`-class.

If a class extends the `VoidBase` class, there is a static attribute called `$config` available which contains an instance of the `Config`-Object. Every attribute you read from this object can be definied in the `consts/environments.php` file. A little Example:


**consts/environments.php**

    $cfg->mysuperfancyclass_somekey = "somevalue";

**some/path/MySuperFancyClass.php**

    namespace Void;

    class MySuperFancyClass extends VoidBase {
      public function myUsefulMethod() {
        echo self::$config->somekey; // will output "somevalue"
      }
    }

### Controllers/

This is the place where all your Controllers are. All your controllers have to have the prefix "Controller" in the class name.

This is a short example for a controller:

    namespace Void;

    class ExampleController extends ControllerBase {

      // this method is nessesary in every controller
      // accessible via /index.php/example
      public function action_index() {
        // fetch all the posts from the database
        // and make them accessible to the view
        $this->posts = Post::all();
      }

      // accessible via /index.php/example/show/1
      //  => $id = 1
      public function action_show($id = null) {
        $this->post = Post::find($id);
      }
    }

### Views/

In here there is a subfolder for every controller which contains a .tpl file for (almost) all methods.

#### The curly braces

In these template you can use PHP between the curly braces `{` & `}`. A little example.

    {if($post->errors):}
      <h3>Errors occured!<h3>
    {endif}

if you put PHP-Code between `{=` & `}`, the return value will be echoed out. Example:

    <h1>{=$post->title}</h1>

The imaginary title of the post will be printed out inside of the `h1`-Tag

But this title might contain some malicious stuff like `script`-tags etc. If you want to savely output text you should use `{>` & `}`. Example:

    {>"<b>This Text is not bold<b>"}

This will convert the `<` to `&lt;`, the `>` to `&gt;` etc.

There is also the possibility to write `{[` & `}`, but this is rarely used and means basicly if the contents inside these brackets is a Template object, it will be rendered automaticly.


#### Template methods

But what if you want to create a Hyperlink inside a template or you want to include an other template? No Problem. There are some methods which you can use. You cann call these methods if you use `:` and the method name after the opening curly brace. A little example:
    
    {:aMethod($param1, 'param2')}
    {>:anOtherMethod('param1')}
    {=:imaginary(15)}
    {>:test()}


##### Create a link

To create a link you can use the `aTag` method as follows:

    {=:aTag("Label", Array('controller', 'action', 'params' =>
        Array('param1')), Array('target', '_blank')}


(**Note**: This should all be on the same line)<br />
this will return you something like this:
    
    <a href="/app/index.php/controller/action/param1" target="_blank">Label</a>



### Views/layout

In this folder, there are template files which are used for the layout of the page. The file `application.tpl` should contain your basic HTML structure and a placeholder like this

    {[$_content}

This is the place where the template of the current controller is rendered.

All other template files in this folder have no special effect. But you can use the following code to include these files.

    {=:render(Array('layout', 'filename'), Array('variablename' => 'value'))}

### Models/ (create it if it doesn't exist!)

Basicly the whole Model part of the framework is based on [PHP-ActiveRecord](http://phpactiverecord.org). The reference on the Website is pretty good, so I think i don't have to write  much more. But let's begin:

For every table in the database you should create a Class in the `Models/` directory. Your table has to be in plural, and the model has to be in singular. For Example

<table>
  <tr>
    <th>tablename</th>
    <th>classname of the Model</th>
  </tr>
  <tr>
    <td>posts</td>
    <td>Post</td>
  </tr>
  <tr>
    <td>users</td>
    <td>User</td>
  </tr>
  <tr>
    <td>comments</td>
    <td>Comment</td>
  </tr>
  <tr>
    <td>tags</td>
    <td>Tag</td>
  </tr>
  <tr>
    <td>categories</td>
    <td>Category</td>
  </tr>
</table>

Lets create a sample Model in `Models/Post.php`

    // must be in the namespace Void for easy access from the Controller
    namespace Void;


    /*
      table structure
      ===============

      Field Type    Null          Key   Default   Extra
      ----------------------------------------------------------
      id            int(11)       PRI   NULL      auto_increment
      title         varchar(255)  NO    NULL  
      body          text          NO    NULL  
      created       datetime      NO    NULL  
      updated       datetime      YES   NULL  
      user_id       int(11)       YES   NULL

    */
    // Every model must extend \ActiveRecord\Model
    // this gives the Model much functionality
    class Post extends \ActiveRecord\Model {
      // which attributes can be changed via form
      static $attr_accessible = Array('title', 'body', 'user_id');
      
      // relationships
      static $belongs_to = Array('user');
      static $has_many = Array('comments', 'tags');
      
      // validations
      static $validates_presence_of = Array(
        Array('title'),
        Array('body')
      );

      static $validates_length_of = Array(
        Array('title', 'maximum' => 255)
      );
      // more validations etc.
      
      // this automaticly updates the 'created' & the 'updated' attributes
      static $before_save = Array('fill_in_created', 'fill_in_updated');
      static $before_update = Array('fill_in_updated');

      public function fill_in_created() {
        $this->created = $this->get_current_time();
      }

      public function fill_in_updated() {
        $this->updated = $this->get_current_time();
      }

      public function get_current_time() {
        return date('d-m-YH:i:s');
      }
    }


### images/

This is the perfect place to throw all your images in ;-)

### javascripts/

The folder to put your javascript files. Well this is obvious.

### stylesheets/

Put your stylesheets in here.

### test/

Some PHP-Unit tests for this framework, only relevant if you do changes to the framework.

### lib/

This is where all the magic is. All the hardcore PHP Code you should'nt touch if you have no idea what you are doing ;-)

### scripts/

In this folder you can find some useful command line tools for this framework.

### scripts/generate

This script helps you creating controllers and the views needed.
  
    $ scripts/generate controller name method1 method2

This command will create the file `Conrollers/NameController.php` with the class declaration and the methods `method1` & `method2` in it.
It also creates the folder `Views/name` and the files `Views/name/method1.tpl` & `Views/name/method2.tpl`.

### autoload.php

This executes the Autoloader to load classes in subfolders automaticly. Only relevant if you do some changes to this framework.

### index.php

This is the main file of the framework, every request passes this file.

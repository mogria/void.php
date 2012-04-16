# void.php
void.php is a simple, small, and fast Framework based on MVC completly written in PHP.

Author
:   Mogria <m0gr14@gmail.com>

License
:   MIT, see `LICENSE`

# Contributing
If you've found any bugs or have suggestions, let me know! You can also fork this project on [GitHub](https://github.com/mogria/void.php) and create a pull request.

# Runnings the Tests
All the tests and related files are in the `test/`-folder. To run the tests simply use

    $ phpunit test/

# How to use void.php
The following few lines should give you a brief instruction into this little Framework.

This framework is based on the MVC principle. If you don't know what this means take a look at the [Wikipedia Page](http://en.wikipedia.org/wiki/Model-view-controller).

## Configuration
The Only file you need to open for configuration is the `config/environments.php` file. But to start you don't need to do ANY configuration.

## Controllers
The Controllers are located in the `Controllers` folder. Every Controller needs to have the Method `action_index()`. The Methods of the Controller are accessible via a simple url. 

    index.php/YourAwesomeControllerName/YourMethodName

 So if I had a Controller called `TestController` and a method called `action_test`. The method will be called if i access `index.php/test/test`. The Method `action_index` will be called if I access `index.php/test/index` or even `index.php/test`.

## Views
For every Controller you create you have to create a folder inside of `Views` with the exact same name. If my Controller were called `TestController` the name of the folder would be `test`. And for every method you create there should be a template file in the folder of the Controller. For example, the name of the file would be `test.tpl` if the name of the action is `action_test`.

## Models
This framework uses [php-activerecord](http://phpactiverecord.org), which is an [ActiveRecord](https://en.wikipedia.org/wiki/Active_record_pattern) implemenation in PHP. Take a look at their Website and learn More about it. Simply place all the Model classes into `Models/`-folder.

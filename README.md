# void.php
void.php is a simple, small, and fast Framework based on MVC principle and completly written in PHP.


# Why should I use a PHP Framework

If you write a PHP-Application without a Framework you'll always write the same Code for certain things like Session handling, User authentification, Database access, Template rendering etc..
If you use a Framework like void.php these things are already here. So you can concentrate on actually coding your Application.

# How to use void.php

There is a little [GitHub Page](http://mogria.github.com/void.php) where you can find a little tutorial.

But some commands to install it:

    $ cd your_webroot
    $ git clone --recursive https://github.com/mogria/void.php.git

It should now be accessible via `http://localhost/void.php/`

__Note__: the `--recursive` flag is required to also fetch the git submodules in this repository. If you forgot to add this flag you can fetch the submodules using `git submodule update --init`

# Contributing
If you've found any bugs or have suggestions, let me know! You can also fork this project on [GitHub](https://github.com/mogria/void.php) and create a pull request.

# Runnings the Tests
All the tests and related files are in the `test/`-folder. To run the tests simply use

    $ make test

[![endorse](http://api.coderwall.com/mogria/endorsecount.png)](http://coderwall.com/mogria)

Author
:   Mogria <m0gr14@gmail.com>

License
:   MIT, see `LICENSE`


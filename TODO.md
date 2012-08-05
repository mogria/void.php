# @TODO

* Generators
  * Parse arguments with getopt (e.g. -f for overwriting)
  * add a possibility for a script to call an other script
* Forms
  * allow more tags
  * better integration with models
* Autoloader
  * you always have to delete the cache to add a new class to the autoloader
* testing
  * proper boot the application in the testing mode (will prevent problems when classes with the same name are in the test/ directory and somewhere else)
* Authentication
  * class ModelAuthentification is useless (ActiveRecord already has an reload method)
* is a default_controller needed, because of the new routing system? change it into an 404 Controller?


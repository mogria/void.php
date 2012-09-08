# @TODO

* Generators
  * Parse arguments with getopt (e.g. -f for overwriting)
* Forms
  * allow more tags
  * better integration with models
* Autoloader
  * you always have to delete the cache to add a new class to the autoloader
* testing
  * proper boot the application in the testing mode (will prevent problems when classes with the same name are in the test/ directory and somewhere else)
* controllers
  * is a default_controller needed, because of the new routing system? change it into an 404 Controller?
  * call a 403 controller when insufficient permission right
  * add possibility to stop render the template
* assets
  * check if the asset system needs changes (because of the new routing system)
* template system
  * its possible to XSS througth the content of an aTag. provide an simple escape function (like h())

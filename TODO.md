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
* assets
  * check if the asset system needs changes (because of the new routing system)
  * probebly needs updateing due new Responde object
  * updating because of redirect loop in index.php?
  * remove feature?
* template
  * has_content() as helper method, rails like? probably not possible easily

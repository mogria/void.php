<?php

namespace Void;

class Cache extends VoidBase {
  public static function cache($identifier, $content = null) {
    !is_dir(self::$config->dir) && @mkdir(self::$config->dir);
    if(!is_writeable(self::$config->dir)) {
      throw new BadMethodCallException("the directory for the cache '" . self::$config->dir . "' is not writeable");
    }

    $dir = dirname($path = self::genPathByIdentifier($identifier));

    !is_dir($dir) && @mkdir($dir);
    if(!is_writeable($dir)) {
      throw new BadMethodCallException("the directory for the cache '" . $dir . "' is not writeable");
    }
    
    if($content === null) {
      return is_file($path) ? unserialize(file_get_contents($path)) : false;
    } else {
      return file_put_contents($path, serialize($content)) !== false;
    }
  }

  public static function genPathByIdentifier($identifier) {
    return self::$config->dir . DS . md5($identifier) . ".tmp";
  }

  public static function cache_modified($identifier) {
    $file = self::genPathByIdentifier($identifier);
    return is_file($file) ? filemtime(self::genPathByIdentifier($identifier)) : 0;
  }

  public static function clear() {
    is_dir(self::$config->dir) && Dir::remove(self::$config->dir);
  }
}

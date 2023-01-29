<?php

  namespace Code\Core;

  class Libraries {
    private $libraries = [];

    private function GetDirectoryPath($name) {
      return LIBPATH . '/' . $name;
    }
    private function GetLibraryPath($name) {
      return LIBPATH . '/' . $name . '.php';
    }
    private function GetLibraryDirPath($name) {
      return LIBPATH . '/' . $name . '/' . $name . '.php';
    }

    private function LoadDirectory($path) {
      $files = scandir($path);

      $directories = [];
      foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
          continue;
        }

        $path_file = $path . '/' . $file;

        if (is_dir($path_file)) {
          $directories[] = $path_file;
        } else {
          require_once $path_file;
        }
      }

      foreach ($directories as $directory) {
        $this->LoadDirectory($directory);
      }
    }

    public function Get(string $name) {
      if (isset($this->libraries[$name])) {
        return $this->libraries[$name];
      }

      $directoryPath = $this->GetDirectoryPath($name);

      if (is_dir($directoryPath) && is_file($this->GetLibraryDirPath($name))) {

        $this->LoadDirectory($directoryPath);
      } else if (is_file($this->GetLibraryPath($name))) {

        require_once $this->GetLibraryPath($name);
      }

      $library_space = '\Code\Libraries\\' . $name;
      
      if (class_exists($library_space)) {
        $this->libraries[$name] = new $library_space();
      } else {
        $this->libraries[$name] = null;
      }

      return $this->libraries[$name];
    }

    protected static $_instance;

    private function __construct() {}

    public static function GetInstance() {
      if (self::$_instance === null) {
        self::$_instance = new self;  
      }

      return self::$_instance;
    }

    private function __clone() {}
    private function __wakeup() {}
  }
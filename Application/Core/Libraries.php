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

        $pathFile = $path . '/' . $file;

        if (is_dir($pathFile)) {
          $directories[] = $pathFile;
        } else {
          require_once $pathFile;
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

      if (is_dir($directoryPath = $this->GetDirectoryPath($name)) && is_file($this->GetLibraryDirPath($name))) {
        $this->LoadDirectory($directoryPath);
      } else if (is_file($this->GetLibraryPath($name))) {
        require_once $this->GetLibraryPath($name);
      }

      $librarySpace = '\Code\Libraries\\' . $name;
      if (class_exists($librarySpace)) {
        $this->libraries[$name] = new $librarySpace();
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
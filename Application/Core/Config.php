<?php

  namespace Code\Core;

  class Config {
    private $configs = [];

    private function GetPath($name) {
      return CONFPATH . '/' . $name . '.php';
    }

    public function Get(string $name) {

      if (!isset($this->configs[$name])) {

        if (is_file($this->GetPath($name))) {
          $this->configs[$name] = require $this->GetPath($name);
        } else {
          $this->configs[$name] = [];
        }
      }

      
      return $this->configs[$name];
      
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
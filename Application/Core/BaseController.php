<?php

  namespace Code\Core;

  class BaseController {
    protected $config_;
    protected $libraries_;

    public function __construct() {
      $this->config_ = Config::GetInstance();
      $this->libraries_ = Libraries::GetInstance();
    }

    protected function LoadLibrary($names) {
      if (!is_array($names)) {
        $names = [ $names ];
      }

      foreach ($names as $name) {
        $this->{$name} = $this->libraries_->Get($name);
      }

    }

    public function GetLibrary(string $name) {
      return $this->libraries_->Get($name);
    }
    
  }
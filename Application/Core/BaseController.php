<?php

  namespace Code\Core;

  class BaseController {
    protected $Config;
    protected $Libraries;
    protected $Controllers;

    public function __construct() {
      $this->Config = Config::GetInstance();
      $this->Libraries = Libraries::GetInstance();
      //$this->Controllers = Controllers::GetInstance();
    }

    protected function LoadLibrary($names) {
      if (!is_array($names)) {
        $names = [ $names ];
      }

      foreach ($names as $name) {
        $this->{$name} = $this->Libraries->Get($name);
      }

    }

    public function GetLibrary(string $name) {
      return $this->Libraries->Get($name);
    }

    protected function GetIpAdress() {
      if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
      } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
      } else {
        return $_SERVER['REMOTE_ADDR'];
      }

      return '';
    }

    protected function GenerateCode(int $len = 16) {
      $code = '';

      while (($len--) !== 0) {
        switch (rand(0, 2)) {
          case 0: $code .= chr(rand(65, 90)); break;
          case 1: $code .= chr(rand(97, 122)); break;
          case 2: $code .= rand(0, 9); break;
        }
      }

      return $code;
    }

    protected function Redirect(string $url, int $statusCode = 301) {
      header('Location: ' . $url, true, $statusCode);
      die();
    }
    
  }
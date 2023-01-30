<?php

  namespace Code\Libraries;

  /**
   * Library for viewing pages with its own variables that you can add using 'AddData' method
   */

  class View extends \Code\Core\BaseController {
    private $data = [];

    public function __construct() {
      parent::__construct();
      
    }

    private function getPath($name) {
      return VIEWPATH . DIRECTORY_SEPARATOR . $name;
    }

    private function getAJAXPath($name) {
      return AJAXPATH . DIRECTORY_SEPARATOR . $name;
    }
    
    public function AddData(string $name, $value) {
      $this->data[$name] = $value;
    }

    public function AddDataArray(array $arr) {
      foreach ($arr as $name => $value) { 
        $this->data[$name] = $value;
      }
    }
    
    public function LoadViewLibraries() {
        $this->LoadLibrary([
            'AppendFiles', 'Route', 'Auth', 'RequestHelper'
        ]);
    }
    
    public function Load(string $name, array $data = null) {
        $path = $this->getPath($name);
        
        if (!is_file($path)) {
          echo $path;
          return false;
        }

        foreach ($this->data as $key => $value) {
          $$key = $value;
        }

        if (is_array($data)) {
          foreach ($data as $key => $value) {
            $$key = $value;
          }
        }

        include $path;
        return true;
    }

    public function Get(string $name, array $data = null) {
      ob_start();

      $this->Load($name, $data);

      $data = ob_get_contents();
      ob_end_clean();

      return $data;
    }
  }
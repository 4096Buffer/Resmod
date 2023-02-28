<?php 
  
  /**
   * Core.php - include files run, controllers
  */

  session_start();
    
  require_once COREPATH . DIRECTORY_SEPARATOR . 'Core.php';
  require_once COREPATH . DIRECTORY_SEPARATOR . 'Libraries.php';
  require_once COREPATH . DIRECTORY_SEPARATOR . 'BaseController.php';
  require_once COREPATH . DIRECTORY_SEPARATOR . 'Config.php';
  require_once CONSPATH . DIRECTORY_SEPARATOR . 'ViewController.php';
  require_once CONSPATH . DIRECTORY_SEPARATOR . 'AJAXController.php';

  $viewController = new \Code\Controllers\ViewController();
  $ajaxController = new \Code\Controllers\AJAXController();
  /*
    Check if request is ajax. if not load normal page or 404 error
  */

  if(!$ajaxController->Run()) {
      $viewController->Run();
  } 

  
?>

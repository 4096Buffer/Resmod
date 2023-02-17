<?php 
	
namespace Code\Controllers;

/**
 * Controller for any AJAX action
*/

class AJAXController extends \Code\Core\BaseController {

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View', 'RequestHelper']);
	}
    
    private function GetControllerPath($controller) {
    
		return CONSPATH . '/' . 'AJAX' . '/' . $controller . '.php';
	}
    
    /**
     * Check if request is from js script by checking if request method
     * is equal to 'POST'.
     * If not return false;
    */

    public function Run() {
        $obj = $this->RequestHelper->GetObjectFromJson();
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ExecuteController($obj['controller'], $obj['action']);    
            return true;
        } 
        
        return false;
    }
    
    /**
     * Execute class from 'AJAX' folder and execute function that was given
    */

    private function ExecuteController($controller, $action) {
        $path = $this->GetControllerPath($controller);
        if(file_exists($path)) {
            require_once $path;

            $class_space = '\Code\Controllers\\AJAX\\' . $controller;
            
            if(class_exists($class_space)) {
                $class = new $class_space(); 
                if(method_exists($class, $action)) { 
                    call_user_func(array($class, $action));
                }
            } 
        }
    }
    
    
    
}



?>
<?php 
	
namespace Code\Controllers;

class AJAXController extends \Code\Core\BaseController {

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View', 'RequestHelper']);
	}
    
    private function GetControllerPath($controller) {
    
		return CONSPATH . DIRECTORY_SEPARATOR . 'AJAX' . DIRECTORY_SEPARATOR . $controller . '.php';
	}
    
    public function Run() {
        $obj = $this->RequestHelper->GetObjectFromJson();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ExecuteController($obj['controller'], $obj['action']);
            
            return true;
        } 
        
        return false;
    }
    
    private function ExecuteController($controller, $action) {
        
        require_once $this->GetControllerPath($controller);

        $class_space = '\Code\Controllers\\AJAX\\' . $controller;
        $class = new $class_space(); 
        
        if(method_exists($class, $action)) { //validate input
            
            call_user_func(array($class, $action));
            
        }
    }
    
    
    
}



?>
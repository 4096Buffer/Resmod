<?php 

namespace Code\Controllers;

/**
 * Controller for viewing pages
 */

class ViewController extends \Code\Core\BaseController {
	
	private $routes = [];
	
	public function __construct() {
    	parent::__construct();

    	$this->LoadLibrary(['Route']);
    }

    private function GetControllerPath($path) {
        return CONSPATH . '/' . $path . '.php';
    }

    /**
     * If current page exists in database show it if it doesnt show 404 page
    */

    

	public function Run() {
        if($this->Route->GetCurrentPage() == null) {
            $this->Route->LoadRouteByUri('/404');
            return;
        }

        $current_page = $this->Route->GetCurrentPage();
        if($current_page['controller'] != null) {
            require_once $this->GetControllerPath($current_page['controller']);

            $class_space = '\Code\Controllers\\' . $current_page['controller'];
            $class = new $class_space();

            call_user_func(array($class, $current_page['action']));
        }

        $this->Route->LoadRoute($current_page);
        
	}
}


?>
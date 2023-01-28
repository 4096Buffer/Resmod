<?php 

namespace Code\Controllers;



class ViewController extends \Code\Core\BaseController {
	
	private $routes          = [];
	
	public function __construct() {
    	parent::__construct();

    	$this->LoadLibrary(['Route']);
    	
    }
    
	public function Run() {
        if($this->Route->GetCurrentPage() == null) {
            $this->Route->LoadRouteByUri('/404');
            return;
        }
        
        $current_page = $this->Route->GetCurrentPage();
        $this->Route->LoadRoute($current_page);
        
	}

    

    
}


?>
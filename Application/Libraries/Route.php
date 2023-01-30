<?php 

namespace Code\Libraries;

/**
 * Library for routing
 */

class Route extends \Code\Core\BaseController {
    
	public function __construct() {
		parent::__construct();
        
		$this->LoadLibrary([ 'DataBase', 'View', 'Variables', 'AppendFiles', 'Module', 'Auth' ]);
        
        $this->SetupRoute();
	}

    private function GetLayoutPath($path) {
        return $path . '.php';   
    }
    
    private function GetLayout($id) {
        $row = $this->DataBase->GetFirstRow("SELECT * FROM layouts WHERE id = ?", [ $id ]);
        return $row;
        
    } 


	private function SetupRoute() {
		$result = $this->DataBase->DoQuery("SELECT * FROM pages");
		$fetches = $this->DataBase->FetchRows($result);
        
		foreach($fetches as $fetch) {
            
			$new_route = [ 
                "uri"    => $fetch['route address'],
                "layout" => $this->GetLayout($fetch['id_layout']),
                "id"     => $fetch['id']
            ];
			$this->routes[] = $new_route;
			
		}
	}
    
    private function AddVariables($id) {
        $vars_c = new \Code\Libraries\Variables($id);
        $module_c = new \Code\Libraries\Module($id);
                
        $this->View->AddData('vars', $vars_c);
        $this->View->AddData('module', $module_c);
    }
    
    public function LoadRoute($route) {
        $layout = $route['layout'];
        $layout_path = $this->GetLayoutPath($layout['view']);
        
        $this->AddVariables($route['id']);
        
        $this->View->LoadViewLibraries();
        $this->View->Load($layout_path);
    }
    
    public function LoadRouteByUri($uri) {
        foreach($this->routes as $route) {
            if($route['uri'] == $uri) {
                $this->LoadRoute($route);
                break;
            }
        }
        
    }
    
    public function GetCurrentPage() {
        $uri = $_SERVER['REQUEST_URI'];
        $uri_ex = explode('/', $uri)[1];
        $found = false;
        
        foreach($this->routes as $route) {
            $layout      = $route['layout'];
            $page_uri    = $route['uri'];
            $page_uri_ex = explode('/', $route['uri'])[1];
            
            if($page_uri_ex == $uri_ex) {
                return $route;
            }
        }
        
        return null;
    }
    
    
    
}


?>
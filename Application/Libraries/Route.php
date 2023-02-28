<?php 

namespace Code\Libraries;

/**
 * Library for routing
 */

class Route extends \Code\Core\BaseController {

    private $path;

	public function __construct() {
		parent::__construct();
        
		$this->LoadLibrary([ 'DataBase', 'View', 'Variables', 'AppendFiles', 'Module', 'Auth', 'RequestHelper']);
        
        $this->SetupRoute();
        $this->PreparePath();
	}

    private function GetLayout($id) {
        $row = $this->DataBase->GetFirstRow("SELECT * FROM layouts WHERE id = ?", [ $id ]);
        return $row;
    } 

    private function PreparePath() {
        $this->path = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

	private function SetupRoute() {
		$fetches = $this->DataBase->Get("SELECT *  FROM pages");
        
		foreach($fetches as $fetch) {
            $layout = $this->GetLayout($fetch['id_layout']);
            $new_route = $fetch;
            
            $new_route['layout']     = $layout;
            unset($new_route['route address']);

            $new_route['uri']        = $fetch['route address'];

			$this->routes[] = $new_route;
		}
	}
    
    private function AddVariables($page) {
        $vars_c = new \Code\Libraries\Variables($page['id']);
        $module_c = new \Code\Libraries\Module($page['id']);
                
        $this->View->AddData('vars', $vars_c);
        $this->View->AddData('module', $module_c);
        $this->View->AddData('current_page', $page);
        
        $data = [
            "profile" => $this->Auth->GetProfile(),
            "page"    => null
        ];

        if($this->GetCurrentPage()['hidden'] == 1) {
            if($this->Auth->IsAuth()) {
                $data['page'] = $this->GetCurrentPage();
            }
        } else {
            $data['page'] = $this->GetCurrentPage();
        }

        foreach($data as $key => $value) {
            $this->AppendFiles->AddData($key, $value);
        }
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
        $found = false;
        
        foreach($this->routes as $route) {
            $layout      = $route['layout'];
            $page_uri    = $route['uri'];
            
            if(preg_match('/^(\/\/)?([^\/]+)?(\/[\s\S]*)?$/', $this->path, $match)) {
                $uri = $match[3];
                $uri_na = '';

                if($pos = \strpos($uri, '?')) {
                    $uri_na = substr($uri, 0, $pos);
                } else {
                    $uri_na = $uri;
                }

                if($uri_na == $page_uri) {
                    if($route['active'] == 1) {
                        return $route;
                    }
                }
            }
        }
        
        foreach($this->routes as $rf) {
            if($rf['uri'] == '/404') {
                return $rf;
                break;
            }
        }
    }
    
}


?>
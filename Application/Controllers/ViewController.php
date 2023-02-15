<?php 

namespace Code\Controllers;

/**
 * Controller for viewing pages
 */

class ViewController extends \Code\Core\BaseController {
	
	private $routes = [];
	
	public function __construct() {
    	parent::__construct();

    	$this->LoadLibrary(['Route', 'View', 'Auth', 'AppendFiles', 'RequestHelper']);
    }

    private function GetControllerPath($path) {
        return CONSPATH . '/' . $path . '.php';
    }

    /**
     * If current page exists in database show it if it doesnt show 404 page
    */
    private function GetLayoutPath($path) {
        return $path . '/Layout.php';   
    }

    private function GetLayoutHiddenPath($path) {
        return $path . '.php';
    }
    
	public function Run() {
        $current_page = $this->Route->GetCurrentPage();

        if($this->Auth->IsAuth() && $this->RequestHelper->GetViewMode()[0] == 'Standard') {
            if($current_page['hidden'] == 0) {
                $this->RequestHelper->SetViewMode('LiveEdit');
            }
        }

        if($this->RequestHelper->GetViewMode()[0] == 'LiveEdit' && !$this->Auth->IsAuth()) {
            $this->RequestHelper->Redirect($_SERVER['QUERY_STRING']);
        }

        if($this->RequestHelper->GetViewMode()[0] == 'LiveEdit') {
            require_once $this->GetControllerPath('LayoutController');
            $class_space = '\Code\Controllers\\LayoutController';
            $class = new $class_space();

            call_user_func(array($class, 'LiveEdit'));
        }

        if($current_page['layout']['controller'] != null) {
            require_once $this->GetControllerPath($current_page['layout']['controller']);
            
            $class_space = '\Code\Controllers\\' . $current_page['layout']['controller'];
            $class = new $class_space();

            call_user_func(array($class, $current_page['layout']['action']));
        }

        $layout      = $current_page['layout'];
        $layout_path = '';

        switch($layout['hidden']) {
            case '0':
                $layout_path = $this->GetLayoutPath($layout['view']);
                break;
            case '1':
                $layout_path = $this->GetLayoutHiddenPath($layout['view']);
                break;
        }
        
        $vars_c = new \Code\Libraries\Variables($current_page['id']);
        $module_c = new \Code\Libraries\Module($current_page['id']);
                
        $this->View->AddData('vars', $vars_c);
        $this->View->AddData('module', $module_c);
        $this->View->AddData('current_page', $current_page);
        
        $data = [
            "profile" => $this->Auth->GetProfile(),
            "page"    => null
        ];

        if($this->Route->GetCurrentPage()['hidden'] == 1) {
            if($this->Auth->IsAuth()) {
                $data['page'] = $this->Route->GetCurrentPage();
            }
        } else {
            $data['page'] = $this->Route->GetCurrentPage();
        }

        foreach($data as $key => $value) {
            $this->AppendFiles->AddData($key, $value);
        }
        
        $this->View->LoadViewLibraries();
        $this->View->Load($layout_path);
        
	}
}


?>
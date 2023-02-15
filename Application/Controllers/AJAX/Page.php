<?php 
	
namespace Code\Controllers\AJAX;

/**
 * AJAX class
 * Page ajax controler
*/

class Page extends \Code\Core\BaseController {

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View', 'RequestHelper', 'Auth', 'Module']);
	}

    /**
     * Get id of page by given pathname
    */
    
    public function GetPageId() {
        $data     = $this->RequestHelper->GetObjectFromJson();
        $pathname = $data['pathname'] ?? null;
        //$pathname = '/' . $pathname;
        $rows     = $this->DataBase->Get("SELECT * FROM `pages` WHERE `route address` = ?", [ $pathname ]);
        
        if(is_null($rows)) {
            $this->RequestHelper->SendJsonData(false, null, 'No page found');
        }

        foreach($rows as $row) {
            $data = [
                "id" => $row['id']
            ];
            
            $this->RequestHelper->SendJsonData(true, $data);
            return;
        }
        
        $this->RequestHelper->SendJsonData(false, null, 'No page found');
    }
    
    /**
     * Add view (+1)
     * to page for statistics
    */
    
    private function UpdateLastView($id) {
        $row            = $this->DataBase->GetFirstRow("SELECT * FROM pages WHERE id = ?", [ '2' ]);
        $last_view      = $row['last_views'];
        $current_time   = \date('m/d/Y h:i:s a', \time());
        $date_current   = new \DateTime($current_time);
        $date_last_view = new \DateTime($last_view);
        
        $diffrence_date = $date_current->diff($date_last_view); //->format('%d-%m-%Y %H-%m-%i-%s');

        if($diffrence_date->m >= 1) { //m
            $result = $this->DataBase->DoQuery("UPDATE pages SET views = '1' WHERE id = ?", [ $id ]);
            $result_date = $this->DataBase->DoQuery("UPDATE pages SET last_views = CURRENT_TIMESTAMP() WHERE id = ?", [ $id ]);
        }
    }

    public function AddView() {
        $data   = $this->RequestHelper->GetObjectFromJson();
        $id     = $data['id'] ?? null;
        $result = $this->DataBase->DoQuery("UPDATE pages SET views = views + 1 WHERE id = ?", [ $id ]);
        
        $this->UpdateLastView($id);

        if($this->DataBase->ErrorCode() !== 0) {
            $this->RequestHelper->SendJsonData(false, null, 'Unknown Error' . $this->DataBase->Error());
            return;
        }
        
        $this->RequestHelper->SendJsonData(true);
    }

    /**
     * Get all pages from database
    */
    
    public function GetPages() {
        $rows   = $this->DataBase->Get("SELECT * FROM pages WHERE hidden = 0");
        $descs  = [];
        
        foreach($rows as $row) {
            $descs[] = $row['description'];    
        }
        
        $this->RequestHelper->SendJsonData(true, $descs);
    }
    
    public function GetViews() {
        $rows   = $this->DataBase->Get("SELECT * FROM pages");
        $views  = [];
        
        foreach($rows as $row) {
            $views[] = $row['views'];
        }
        
        $this->RequestHelper->SendJsonData(true, $views);
    }

    public function GetPageVariables() {
        $data        = $this->RequestHelper->GetObjectFromJson();
        $id          = $data['id'] ?? null;
        $variables = [];

        if(is_null($data)) {
            $this->RequestHelper->SendJsonData(false, null, 'No data has been provided');
            return;
        }
        
        //$page_hidden = $this->DataBase->GetFirstRow("SELECT * FROM pages WHERE id = ?");


        $modules = $this->Module->GetModules();
        
        foreach($modules as $module) {
            if($module['id_page'] !== $id) {
                continue;
            }
        
            $get_variables = $this->DataBase->Get("
                SELECT
                mvv.*, mv.name, mv.type, mv.default_value
                FROM
                modules_variables_values 
                mvv INNER JOIN modules_variables mv 
                ON mv.id = mvv.id_variable WHERE mvv.id_module = ?", [ $module['id'] ]);

            $module_variables = [
                "module"    => $module,
                "variables" => []
            ];

            foreach($get_variables as $variable) {
                $module_variables["variables"][] = $variable;
            }

            $variables[] = $module_variables;
        }

        
        $this->RequestHelper->SendJsonData(true, $variables);
    }

    public function ChangeTemplate() {
        $data = $this->RequestHelper->GetObjectFromJson();

        $id_page     = $data['id_page'] ?? null;
        $id_template = $data['id_template'] ?? null;

        if(is_null($data)) {
            $this->RequestHelper->SendJsonData(false, null, 'No data has been provided');
            return;
        }

        if(!$this->Auth->IsAuth()) {
            $this->RequestHelper->SendJsonData(false, null, 'User is not authorized');
            return;
        }

        $page = $this->DataBase->GetFirstRow("SELECT * FROM pages WHERE id = ?", [ $id_page ]);
        $update_result = $this->DataBase->DoQuery("UPDATE pages SET id_layout = ? WHERE id = ?", [ $id_template, $page['id']]);

        if($this->DataBase->ErrorCode() !== 0) {
            $this->RequestHelper->SendJsonData(false, null, 'Unknown database error!');
            return;
        }

        $this->RequestHelper->SendJsonData(true);
    }

    public function ChangeActive() {
        $data = $this->RequestHelper->GetObjectFromJson();

        $id_page     = $data['id_page'] ?? null;
        $active      = $data['active'] ?? null;

        if(is_null($data)) {
            $this->RequestHelper->SendJsonData(false, null, 'No data has been provided');
            return;
        }

        if(!$this->Auth->IsAuth()) {
            $this->RequestHelper->SendJsonData(false, null, 'User is not authorized');
            return;
        }

        $result = $this->DataBase->DoQuery("UPDATE pages SET active = ? WHERE id = ?", [ $active, $id_page ]);

        if($this->DataBase->ErrorCode() != 0) {
            $this->RequestHelper->SendJsonData(false, null, 'Unknown db error');
            return;
        }

        $this->RequestHelper->SendJsonData(true);
    }

    
}



?>
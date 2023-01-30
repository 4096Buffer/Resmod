<?php 
	
namespace Code\Controllers\AJAX;

/**
 * AJAX class
 * Page ajax controler
*/

class Page extends \Code\Core\BaseController {

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View', 'RequestHelper', 'Auth']);
	}

    /**
     * Get id of page by given pathname
    */
    
    public function GetPageId() {
        $data     = $this->RequestHelper->GetObjectFromJson();
        $pathname = $data['pathname'];
        //$pathname = '/' . $pathname;
        $result   = $this->DataBase->DoQuery("SELECT * FROM `pages` WHERE `route address` = ?", [ $pathname ]);
        $rows = $this->DataBase->FetchRows($result); 
        
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

    public function AddView() {
        $data   = $this->RequestHelper->GetObjectFromJson();
        $id     = $data['id'];
        $result = $this->DataBase->DoQuery("UPDATE pages SET views = views + 1 WHERE id = ?", [ $id ]);
        
        if($this->DataBase->ErrorCode() !== 0) {
            $this->RequestHelper->SendJsonData(false, null, 'Unknown Error');
            return;
        }
        
        $this->RequestHelper->SendJsonData(true);
    }

    /**
     * Get all pages from database
    */
    
    public function GetPages() {
        $result = $this->DataBase->DoQuery("SELECT * FROM pages WHERE hidden = 0");
        $rows   = $this->DataBase->FetchRows($result);
        $descs  = [];
        
        foreach($rows as $row) {
            $descs[] = $row['description'];    
        }
        
        $this->RequestHelper->SendJsonData(true, $descs);
    }
    
    public function GetViews() {
        $result = $this->DataBase->DoQuery("SELECT * FROM pages");
        $rows   = $this->DataBase->FetchRows($result);
        $views  = [];
        
        foreach($rows as $row) {
            $views[] = $row['views'];
        }
        
        $this->RequestHelper->SendJsonData(true, $views);
    }
    
}



?>
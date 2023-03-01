<?php 
	
namespace Code\Controllers\AJAX;

class ArticlesAJAX extends \Code\Core\BaseController { //Added AJAX to class name because it was colliding with Articles library class

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View', 'RequestHelper', 'Auth', 'Articles']);
    }
    
    public function CreateArticle() {
        if(!$this->Auth->IsAuth()) {
            $this->RequestHelper->SendJsonData(false, null, 'User is not authorized');
            return;
        }

        $data = $this->RequestHelper->GetObjectFromJson();

        if(is_null($data)) {
            $this->RequestHelper->SendJsonData(false, null, 'No data has been provided');
            return;
        }

        $create_data = $data['create_data'] ?? null;
        
        $used_data = [
            'title',
            'content',
            'image',
            'link_type',
            'category',
            'state',
            'comments_enabled',
            'author_id'
        ];

        foreach($used_data as $ud) {
            if(!array_key_exists($ud, $create_data)) {
                $this->RequestHelper->SendJsonData(false, null, 'Invalid create data');
                return;
            }
        }

        

        if(!$created = $this->Articles->Create($create_data)) {
            $this->RequestHelper->SendJsonData(false, null, 'Unknown DB error');
            return;
        }

        $this->RequestHelper->SendJsonData(true, $created);
    }

    public function GetArticleData() {
        $perms = false;
        if($this->Auth->IsAuth()) {
           $perms = true; 
        }

        $data = $this->RequestHelper->GetObjectFromJson();

        if(is_null($data)) {
            $this->RequestHelper->SendJsonData(false, null, 'No data has been provided');
            return;
        }

        $id = $data['id'] ?? null;

        if(is_null($id)) {
            $this->RequestHelper->SendJsonData(false, null, 'Invalid id');
            return;
        }

        $get = $this->DataBase->GetFirstRow("SELECT * FROM articles WHERE id = ?", [ $id ]);

        if(is_null($get)) {
            $this->RequestHelper->SendJsonData(false, null, 'Invalid id');
            return;
        }

        /**
         * Status
         * 1 - Public (public)
         * 2 - In-build (private, not posted)
         * 3 - Private (posted, private)
        */

        if($get['state'] != '1' && !$perms) {
            $this->RequestHelper->SendJsonData(false, null, 'Forbidden action');
            return;
        }

        $this->RequestHelper->SendJsonData(true, $get);
    }

    public function RemoveArticle() {
        if(!$this->Auth->IsAuth()) {
            $this->RequestHelper->SendJsonData(false, null, 'User is not authorized');
            return;
        }

        $data = $this->RequestHelper->GetObjectFromJson();

        if(is_null($data)) {
            $this->RequestHelper->SendJsonData(false, null, 'No data has been provided');
            return;
        }

        $id = $data['id'] ?? null;

        if(is_null($data['id'])) {
            $this->RequestHelper->SendJsonData(false, null, 'Invalid id');
            return;
        }

        $exists = $this->DataBase->Get("SELECT * FROM articles WHERE id = ?", [ $id ]);

        if(is_null($exists)) {
            $this->RequestHelper->SendJsonData(false, null, 'Invalid id');
            return;
        }

        $delete = $this->DataBase->DoQuery("DELETE FROM articles WHERE id = ?", [ $id ]);

        if($this->DataBase->ErrorCode() !== 0) {
            $this->RequestHelper->SendJsonData(false, null, 'Unknown DB error');
            return;
        }

        $this->RequestHelper->SendJsonData(true);
    }
    
    public function GetLastArticle() {
        $perms = false;
        if($this->Auth->IsAuth()) {
           $perms = true; 
        }

        $get = $this->DataBase->Get("SELECT * FROM articles");

        if(is_null($get)) {
            $this->RequestHelper->SendJsonData(false, null, 'No article found');
            return;
        }

        $get = \end($get);

        /**
         * Status
         * 1 - Public (public)
         * 2 - In-build (private, not posted)
         * 3 - Private (posted, private)
        */

        if($get['state'] != '1' && !$perms) {
            $this->RequestHelper->SendJsonData(false, null, 'Forbidden action');
            return;
        }

        $this->RequestHelper->SendJsonData(true, $get);
    }
}


//such a goofy class :P
?>
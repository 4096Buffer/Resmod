<?php 
	
namespace Code\Controllers\AJAX;

/**
 * AJAX class
 * Controller for admin accounts
*/

class Users extends \Code\Core\BaseController {

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View', 'RequestHelper', 'Auth']);
	}
    
    /**
     * Ajax function 'Login' 
     * As the name of function says it's used for loging user
     * Send data (in json) using RequestHelper library.
     */

    public function ChangeActive() {
        if(!$this->Auth->IsAuth()) {
            $this->RequestHelper->SendJsonData(false, null, 'User is not authorized');
            return;
        }

        $data = $this->RequestHelper->GetObjectFromJson();

        if(is_null($data)) {
            $this->RequestHelper->SendJsonData(false, null, 'No data has been provided');
            return;
        }

        $id_user     = $data['id_user'] ?? null;
        $active      = $data['active'] ?? null;

        $result = $this->DataBase->DoQuery("UPDATE users SET active = ? WHERE id = ?", [ $active, $id_user ]);

        if($this->DataBase->ErrorCode() != 0) {
            $this->RequestHelper->SendJsonData(false, null, 'Unknown DB error');
            return;
        }

        $this->RequestHelper->SendJsonData(true);
    }

    public function ChangeData() {
        if(!$this->Auth->IsAuth()) {
            $this->RequestHelper->SendJsonData(false, null, 'User is not authorized');
            return;
        }

        $data = $this->RequestHelper->GetObjectFromJson();

        if(is_null($data)) {
            $this->RequestHelper->SendJsonData(false, null, 'No data has been provided');
            return;
        }

        $id_user = $data['id_user'] ?? null;
        $data    = $data['data'] ?? null;

        $used_data = [
            'name', 'surname', 'email', 'login', 'bio'
        ];

        foreach($data as $key => $value) {
            if(!array_key_exists($key, $used_data)) {
                $this->RequestHelper->SendJsonData(false, null, 'Invalid data');
                return;
            }

            $update = $this->DataBase->DoQuery("UPDATE users SET " . $key . "= ? WHERE id = ?", [ $value, $id_user ]);
        }

        if($this->DataBase->ErrorCode() !== 0) {
            $this->RequestHelper->SendJsonData(false, null, 'Unknown DB error');
            return;
        }

        $this->RequestHelper->SendJsonData(true);
    }
    
}



?>
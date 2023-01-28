<?php 
	
namespace Code\Controllers\AJAX;

class Login extends \Code\Core\BaseController {

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View', 'RequestHelper', 'Auth']);
	}
    
    /*
    private function GetUserId($login) {
        $result = $this->DataBase->DoQuery("SELECT * FROM users WHERE login = ?", [ $login ]);
        
        if($result->num_rows == 1) {
            $rows = $result->fetch_assoc();
            return $rows['id'];
        } 
        
        return 0;
    }
    
    */
    
    public function Login() {
        $data = $this->RequestHelper->GetObjectFromJson();
        if(isset($data['login']) && isset($data['password'])) {
            
            $data['login'] = htmlspecialchars($data['login']);
            
            $result = $this->DataBase->DoQuery("SELECT * FROM users WHERE login = ?", [ $data['login'] ]);
            
            if($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                
                if($this->Auth->IsAuth()) {
                    $this->RequestHelper->SendJsonData(false, null, 'User is already logged');
                    return;
                }
                
                if(password_verify($data['password'], $row['password'])) {
                    $send_data = [
                        "id"    => $row['id'],
                        "login" => $row['login']
                    ];
                    
                    $this->Auth->AuthUser($row['id'], $row['login']);
                    
                    $this->RequestHelper->SendJsonData(true, $send_data);
                    
                } else {
                   
                    $this->RequestHelper->SendJsonData(false, null, 'Invalid password');
                }
                
            } else {
                $this->RequestHelper->SendJsonData(false, null, 'No user found with login "' . $data['login'] . '"');
            }
            
       } else {
         $this->RequestHelper->SendJsonData(false, null, 'Invalid request data (no login or password has been passed)'); 
       }
        
    }
    
    public function Logout() {
        if(isset($_SESSION['id'])) {
            $this->Auth->UnAuth($_SESSION['id']);
            
            $this->RequestHelper->SendJsonData(true);
        } else {
            $this->RequestHelper->SendJsonData(false, null, 'User is not logged (?)');
        }
    }
    
    public function GetAdminProfile() {
        if($this->Auth->IsAuth()) {
            $send_data = [
                "id"    => $_SESSION['id'],
                "login" => $_SESSION['login']
            ];
            
            $this->RequestHelper->SendJsonData(true, $send_data);
        } else {
            $this->RequestHelper->SendJsonData(false, null, 'User is not logged');
        }
        
    }
    
}



?>
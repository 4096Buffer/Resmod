<?php 

namespace Code\Libraries;

class Auth extends \Code\Core\BaseController {


	public function __construct() {
		parent::__construct();

		$this->LoadLibrary('DataBase');
	}
    
    private function GetActiveUsers() {
        $result = $this->DataBase->DoQuery("SELECT * FROM users WHERE active = ?", [ '1' ]);
        return $result;
    }
    
    private function RemoteLogout($user_id) {
        $result = $this->DataBase->DoQuery("UPDATE users SET active = ? WHERE id = ?", [ '0', $user_id ]);
        
        if($this->DataBase->ErrorCode() != 0) {
            die('Error logging off other user who is currently using CMS!');
        }
    }
    
    private function SetLastActive($user_id) {
        $mysql_date_now = date("Y-m-d H:i:s");
        $result = $this->DataBase->DoQuery("UPDATE users SET last_active = NOW() WHERE id = ?", [ $user_id ]);
    }
    
    public function AuthUser($id, $login) {
        $active_users = $this->GetActiveUsers();
        
        if($active_users->num_rows != 0) {
            $rows = $this->DataBase->FetchRows($active_users);
            
            foreach($rows as $row) {
                $this->RemoteLogout($row['id']);
            }
        }
        
        $_SESSION['id'] = $id;
        $_SESSION['login'] = $login;
        
        $result = $this->DataBase->DoQuery("UPDATE users SET active = ? WHERE id = ?", [ '1', $id ]);
        
        $this->SetLastActive($id);
        
        if($this->DataBase->ErrorCode() != 0) {
            die('Error authing user ' . $login . '. Please try to login again :(');
            unset($_SESSION['id']);
            unset($_SESSION['login']);
        }
        
        
    }
    
    public function IsAuth() {
        if(isset($_SESSION['login'])) {
            $id = $_SESSION['id'];
            $result = $this->DataBase->DoQuery("SELECT * FROM users WHERE id = ?", [ $id ]);
            
            if($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                if($row['active'] == 0) {
                    return false;
                }
                
                return true;
            }
            
            return false;
        }
        
        return false;
    }
    
    public function UnAuth($user_id) {
        if(isset($_SESSION['login'])) {
            $result = $this->DataBase->DoQuery("UPDATE users SET active = ? WHERE id = ?", [ '0', $user_id ]);

            unset($_SESSION['id']);
            unset($_SESSION['login']);
        }
    }
}


?>
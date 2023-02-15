<?php 

namespace Code\Libraries;

/**
 * Library for authing user, unauthing or checking if user is authed (logged as admin)
 */

class Auth extends \Code\Core\BaseController {


	public function __construct() {
		parent::__construct();

		$this->LoadLibrary('DataBase');
	}
    
    private function GetActiveUsers() {
        $rows = $this->DataBase->Get("SELECT * FROM admin_users WHERE active = ?", [ '1' ]);
        
        if($rows) {
            return $rows;
        }

        return [];
    }
    
    private function RemoteLogout($user_id) {
        $result = $this->DataBase->DoQuery("UPDATE admin_users SET active = ? WHERE id = ?", [ '0', $user_id ]);
        
        if($this->DataBase->ErrorCode() != 0) {
            die('Error logging off other user who is currently using CMS!');
        }
    }
    
    private function SetLastActive($user_id) {
        $mysql_date_now = date("Y-m-d H:i:s");
        $result = $this->DataBase->DoQuery("UPDATE admin_users SET last_active = NOW() WHERE id = ?", [ $user_id ]);
    }
    
    public function AuthUser($id, $login, $avatar, $name, $surname, $email) {
        $active_users = $this->GetActiveUsers();
        
        if(count($active_users) != 0) {
            $rows = $active_users;
            
            foreach($rows as $row) {
                $this->RemoteLogout($row['id']);
            }
        }
        
        $_SESSION['id']      = $id;
        $_SESSION['login']   = $login;
        $_SESSION['avatar']  = $avatar;
        $_SESSION['name']    = $name;
        $_SESSION['surname'] = $surname;
        $_SESSION['email']   = $email;
        
        $result = $this->DataBase->DoQuery("UPDATE admin_users SET active = ? WHERE id = ?", [ '1', $id ]);
        
        $this->SetLastActive($id);
        
        if($this->DataBase->ErrorCode() != 0) {
            die('Error authing user ' . $login . '. Please try to login again :(');
            unset($_SESSION['id']);
            unset($_SESSION['login']);
            unset($_SESSION['avatar']);
            unset($_SESSION['name']);
            unset($_SESSION['surname']);
            unset($_SESSION['email']);
        }
        
        
    }
    
    public function IsAuth() {
        if(isset($_SESSION['login'])) {
            $id = $_SESSION['id'];
            $row = $this->DataBase->GetFirstRow("SELECT * FROM admin_users WHERE id = ?", [ $id ]);

            if($row) {
                if($row['active'] == 0) {
                    $this->UnAuth($id);
                    return false;
                }
                
                return true;
            }
            
            return false;
        }
        
        return false;
    }
    
    public function UnAuth($user_id) {
        if($this->IsAuth()) {
            $result = $this->DataBase->DoQuery("UPDATE admin_users SET active = ? WHERE id = ?", [ '0', $user_id ]);

            unset($_SESSION['id']);
            unset($_SESSION['login']);
        }
    }

    public function GetProfile($id = null) {
        if($this->IsAuth()) {
            if(!$id) {
                return [
                    "id"      => $_SESSION['id'] ?? 0,
                    "login"   => $_SESSION['login'] ?? '',
                    "avatar"  => 'Uploads/Avatars/' . $_SESSION['avatar'] ?? '',
                    "name"    => $_SESSION['name'] ?? '',
                    "surname" => $_SESSION['surname'] ?? '',
                    "email"   => $_SESSION['email'] ?? ''
                ];
            } else {
                $profile = $this->DataBase->GetFirstRow("SELECT * FROM admin_users WHERE id = ?", [ $id ]);
                
                return [
                    "id"      => $profile['id'] ?? 0,
                    "login"   => $profile['login'] ?? '',
                    "avatar"  => 'Uploads/Avatars/' . $profile['avatar'] ?? '',
                    "name"    => $profile['name'] ?? '',
                    "surname" => $profile['surname'] ?? '',
                    "email"   => $profile['email'] ?? ''
                ];
            }
        }
    }
}


?>
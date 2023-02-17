<?php 

namespace Code\Libraries;

/**
 * Library for authing user, unauthing or checking if user is authed (logged as admin)
 */

class Auth extends \Code\Core\BaseController {
    private $profile;

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'RequestHelper']);

        if(!$this->profile && isset($_SESSION['id'])) {
            $this->profile = [
                'id'      => $_SESSION['id'],
                'login'   => $_SESSION['login'],
                'avatar'  => 'Uploads/Avatars/' . $_SESSION['avatar'],
                'name'    => $_SESSION['name'],
                'surname' => $_SESSION['surname'],
                'email'   => $_SESSION['email']
            ];
        }

        
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
        
        $this->profile = [
            'id'      => $id,
            'login'   => $login,
            'avatar'  => $avatar,
            'name'    => $name,
            'surname' => $surname,
            'email'   => $email
        ];

        foreach($this->profile as $key => $value) {
            $_SESSION[$key] = $value;
        }
        
        
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
        if($this->profile) {
            $id = $_SESSION['id'];
            $row = $this->DataBase->GetFirstRow("SELECT * FROM admin_users WHERE id = ?", [ $id ]);

            if($row) {
                if($row['active'] == 0 || $row['enabled'] == 0) {
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
        $result = $this->DataBase->DoQuery("UPDATE admin_users SET active = 0 WHERE id = ?", [ $user_id ]);

        unset($_SESSION['id']);
        unset($_SESSION['login']);
        unset($_SESSION['avatar']);
        unset($_SESSION['name']);
        unset($_SESSION['surname']);
        unset($_SESSION['email']);

        $this->RequestHelper->Redirect('/');
    }

    public function GetProfile($id = null) {
        if($this->IsAuth()) {
            if(!$id) {
                return $this->profile;
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
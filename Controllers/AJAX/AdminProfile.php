<?php 
	
namespace Code\Controllers\AJAX;

/**
 * AJAX class
 * Controller for admin accounts
*/

class AdminProfile extends \Code\Core\BaseController {

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View', 'RequestHelper', 'Auth']);
	}
    
    /**
     * Ajax function 'Login' 
     * As the name of function says it's used for loging user
     * Send data (in json) using RequestHelper library.
     */

    public function Login() {
        $data = $this->RequestHelper->GetObjectFromJson();
        if(isset($data['login']) && isset($data['password'])) {
            
            $data['login'] = htmlspecialchars($data['login']);
            
            $row= $this->DataBase->GetFirstRow("SELECT * FROM admin_users WHERE login = ?", [ $data['login'] ]);
            
            if($row) {
                if($this->Auth->IsAuth()) {
                    $this->RequestHelper->SendJsonData(false, null, 'User is already logged');
                    return;
                }
                
                if(password_verify($data['password'], $row['password'])) {
                    if($row['enabled'] == 0) {
                        $this->RequestHelper->SendJsonData(false, null, 'Your account has been disabled!');
                        return;
                    }
                    $send_data = [
                        "id"      => $row['id'],
                        "login"   => $row['login'],
                        "avatar"  => $row['avatar'],
                        "name"    => $row['name'],
                        "surname" => $row['surname'],
                        "email"   => $row['email']
                    ];
                    
                    $this->Auth->AuthUser($row['id'], $row['login'], $row['avatar'], $row['name'], $row['surname'], $row['email']);
                    
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
    
    /**
     * Logout user using Auth library.
    */

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
                "id"     => $_SESSION['id'],
                "login"  => $_SESSION['login'],
                "avatar" => $_SESSION['avatar']
            ];
            
            $this->RequestHelper->SendJsonData(true, $send_data);
        } else {
            $this->RequestHelper->SendJsonData(false, null, 'User is not logged');
        }
    }

    public function ChangeEnabled() {
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
        $enabled      = $data['enabled'] ?? null;

        $result = $this->DataBase->DoQuery("UPDATE admin_users SET `enabled` = ? WHERE id = ?", [ $enabled, $id_user ]);

        if($this->DataBase->ErrorCode() != 0) {
            $this->RequestHelper->SendJsonData(false, null, 'Unknown DB error');
            return;
        }

        $this->RequestHelper->SendJsonData(true);
    }

    private function GetProfileIfPass($id, $password) {
        $user = $this->DataBase->GetFirstRow("SELECT * FROM admin_users WHERE id = ?", [ $id ]);
        
        if(password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
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

        $id_admin = $data['id_admin'] ?? null;
        $sdata     = $data['data'] ?? null;
        $profile  = $data['profile'] ?? null;

        if(is_null($this->GetProfileIfPass($profile['id'], $profile['password']))) {
            $this->RequestHelper->SendJsonData(false, null, 'Invalid password provided!');
            return;
        }

        $profile = $this->GetProfileIfPass($profile['id'], $profile['password']);

        $admin_profile = $this->DataBase->GetFirstRow("SELECT * FROM admin_users WHERE id = ?", [ $id_admin ]);
        
        if(is_null($admin_profile)) {
            $this->RequestHelper->SendJsonData(false, null, 'Invalid admin id provided!');
            return;
        }
        
        if($profile['level'] > $admin_profile['level'] || $profile['level'] == $admin_profile['level']) {
            $this->RequestHelper->SendJsonData(false, null, 'You dont have priviliges to do this action!');
            return;
        }

        $data_allowed = [
            'name',
            'surname',
            'login',
            'email',
            'avatar'
        ];

        foreach($data_allowed as $value) {
            if(!isset($sdata[$value])) {
                continue;
            }

            $update = $this->DataBase->DoQuery("UPDATE admin_users SET " . $value . "= ? WHERE id = ?", [ $sdata[$value], $id_admin ]);
        }

        if($this->DataBase->ErrorCode() !== 0) {
            $this->RequestHelper->SendJsonData(false, null, 'Unknown DB error');
            return;
        }

        $this->RequestHelper->SendJsonData(true);
    }

    private function LastCreatedAdmin() {
        $admins = $this->DataBase->Get("SELECT * FROM admin_users");
        return \end($admins);
    }

    public function CreateAdminAccount() {
        if(!$this->Auth->IsAuth()) {
            $this->RequestHelper->SendJsonData(false, null, 'User is not authorized');
            return;
        }

        $data = $this->RequestHelper->GetObjectFromJson();

        if(is_null($data)) {
            $this->RequestHelper->SendJsonData(false, null, 'No data has been provided');
            return;
        }

        if(is_null($data['profile_data'])) {
            $this->RequestHelper->SendJsonData(false, null, 'Profile data are null');
            return;
        }

        $profile_data = json_decode($data['profile_data'], true);

        if(!is_file($profile_data['avatar'])) {
            $this->RequestHelper->SendJsonData(false, null, 'Invalid avatar path');
            return;
        }

        if($this->DataBase->Exists($this->DataBase->DoQuery("SELECT * FROM admin_users WHERE login = ? OR email = ?", [ $profile_data['login'], $profile_data['email'] ])) == false) {
            $new_level     = $this->LastCreatedAdmin()['level'] + 1;
            $hash_password = password_hash($profile_data['password'], PASSWORD_BCRYPT);
            $created_acc = $this->DataBase->DoQuery("INSERT INTO admin_users VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, current_timestamp(), 0, 1)", [ $profile_data['name'], $profile_data['surname'], $profile_data['login'],$profile_data['email'], $hash_password, basename($profile_data['avatar']), $new_level ]);

            if($this->DataBase->ErrorCode() !== 0) {

                $this->RequestHelper->SendJsonData(false, null, 'Unknown DB error');
                return;
            }

            $created_acc = $this->LastCreatedAdmin();
            $this->RequestHelper->SendJsonData(true, $created_acc);
            return;
        }

        unlink($profile_data['avatar']);

        $this->RequestHelper->SendJsonData(false, null, 'Login or email is already used by someone else');
    }

    
}



?>
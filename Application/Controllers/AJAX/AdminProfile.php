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
    
}



?>
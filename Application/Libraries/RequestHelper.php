<?php 

namespace Code\Libraries;

/**
 * Helper for request actions getting ip, getting sent json, or sending json back
 */

class RequestHelper extends \Code\Core\BaseController {
    
	public function __construct() {
		parent::__construct();

		$this->LoadLibrary('DataBase');
	}

    public function SendJsonData($success, $data = null, $reason = null) {
        $json = [];
        
        if($success) {
            $json['response'] = 'Success';
            
            if(!is_null($data)) {
                $json['data'] = $data;
            }
            
        } else {
            $json['response'] = 'Error';
            
            if(!is_null($reason))  {
                $json['reason'] = $reason;
            } else {
                $json['reason'] = '';
            }
        }
        
        $json_encoded = json_encode($json);
        
        die($json_encoded);
    }
    
    public function GetObjectFromJson() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
            
        return $data;
    }

    public function GetIpAdress() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
          return $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
          return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
          return $_SERVER['REMOTE_ADDR'];
        }
  
        return '';
    }
  
    public function Redirect(string $url, int $statusCode = 301) {
        header('Location: ' . $url, true, $statusCode);
        die();
    }

    public function RandomHash($len = 22) {
        return bin2hex(mcrypt_create_iv($len, MCRYPT_DEV_URANDOM));
    }
    
}


?>
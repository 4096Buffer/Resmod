<?php 

namespace Code\Libraries;

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
}


?>
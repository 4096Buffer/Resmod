<?php 

namespace Code\Libraries;

/**
 * Helper for request actions getting ip, getting sent json, or sending json back
 */

class RequestHelper extends \Code\Core\BaseController {

    private $view_modes  = [];
    private $content_type = [];
    private $json  = [];
    private $post =  [];
	
    public function __construct() {
		parent::__construct();

		$this->LoadLibrary('DataBase');
        $this->view_modes = [
            'LiveEdit' => 'live-edit',
            'Standard' => ''
        ];

        $headers = getallheaders();
        if (array_key_exists('Content-Type', $headers)) {
            $this->content_type = $headers['Content-Type'];
        } else if (array_key_exists('content-type', $headers)) {
            $this->content_type = $headers['content-type'];
        } else if (array_key_exists('Content-type', $headers)) {
            $this->content_type = $headers['Content-type'];
        } else {
            $this->content_type = 'text/plain';
        }

        if ($this->content_type === 'application/json') {
            $this->json = json_decode(trim(file_get_contents('php://input')), true);
        }
        
        $this->post = $_POST;
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
    
    public function GetJSON(string $key = null) {
        if(!is_null($key)) {
            if(array_key_exists($key, $this->json)) {
                return $this->json[$key];
            }

            return null;
        }

        return $this->json;
    }

    public function GetPost(string $key = null) {
        if(!is_null($key)) {
            if(array_key_exists($key, $this->post)) {
                return $this->post[$key];
            }

            return null;
        }

        return $this->post;
    }

    public function GetObjectFromJson() {
        if ($this->content_type === 'application/json') {
            return $this->GetJSON();
        } else {
            return $this->GetPost();
        }
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

    public function GetHref() {
        return $_SERVER['REQUEST_URI'] ?? '/';
    }


    public function GetPathname() {
        return explode('?', $this->GetHref())[0];
    }

    public function GetQueryString() {
        //$uri = substr_replace($_SERVER['REQUEST_URI'], '', 0, strpos($_SERVER['REQUEST_URI'], '?'));
        $query = explode('?', $this->GetHref())[1] ?? null;
        return $query;
    }

    public function GetQueryFragments() {
        $query_fragments = explode('&', $this->GetQueryString());
        $frags = [];

        foreach($query_fragments as $frag) {
            $frag = trim($frag);

            if($frag === '') {
                continue;
            }

            $frag_ex = explode('=', $frag);
            $frags[$frag_ex[0]] = $frag_ex[1] ?? null;
        }

        return $frags;
    }

    public function GetViewModes() {
        return $this->view_modes;
    }

    public function GetViewMode() {
        $query = $this->GetQueryFragments();
        $mode = '';

        if(isset($query['mode'])) {
            switch($query['mode']) {
                case 'live-edit':
                    $mode = 'LiveEdit';
                    break;
                default:
                    $mode = 'Standard';
                    break;
            }
        } else {
            $mode = 'Standard';
        }

        return array(
            $mode,
            $this->view_modes[$mode]
        );
    }

    public function SetViewMode($mode) {
        $query = $this->GetQueryFragments();
        if(!isset($query[$mode])) {
            $char = '?';
            if(!empty($query)) {
                $char = '&';
            }
            $this->Redirect($this->GetHref() . $char . 'mode=' . $this->view_modes[$mode]);
        }
    }

}


?>